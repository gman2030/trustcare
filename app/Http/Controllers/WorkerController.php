<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\SparePart;
use App\Models\SparePartOrder;
use Barryvdh\DomPDF\Facade\Pdf;

class WorkerController extends Controller
{
    public function dashboard()
    {

        $tasks = Message::where('worker_name', Auth::user()->name)
            ->latest()
            ->get();

        return view('worker.dashboard', compact('tasks'));
    }
    public function workerDashboard()
    {
        $currentWorker = Auth::user()->name;

        $tasks = Message::where('worker_name', $currentWorker)
            ->whereNotIn('status', ['Completed', 'completed', 'COMPLETED'])
            ->with('user')
            ->latest()
            ->get();

        foreach ($tasks as $task) {
            $parts = explode(': ', $task->subject);
            $task->extracted_sn = isset($parts[1]) ? trim($parts[1]) : 'N/A';
        }

        return view('worker.dashboard', compact('tasks'));
    }

    // قبول المهمة
    public function acceptTask($id)
    {
        $message = Message::findOrFail($id);
        $message->update(['status' => 'Accepted']);

        return back()->with('success', 'Task accepted. You can start working now.');
    }

    // إتمام المهمة (تغيير الحالة لتختفي من القائمة ولكن تبقى في قاعدة البيانات)
    public function completeTask($id)
    {
        $message = Message::findOrFail($id);
        $message->update(['status' => 'COMPLETED']);

        return back()->with('success', 'Task marked as completed and moved to archive.');
    }

    // مسح المهمة نهائياً (حذف من قاعدة البيانات)
    public function destroyTask($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return back()->with('success', 'Task has been permanently deleted.');
    }
    // جلب وعرض قطع الغيار للعامل فقط (عرض فقط)
    public function sparePage()
    {
        $products = \App\Models\Product::all();
        return view('worker.spare', compact('products'));
    }
    public function searchProduct($sn)
    {
        // البحث عن القطعة بواسطة الرقم التسلسلي
        $product = Product::where('sn', $sn)->first();

        if (!$product) {
            return response()->json(['error' => 'No parts found for this serial number.'], 404);
        }

        // إرجاع البيانات بصيغة JSON ليفهمها كود الـ JavaScript في الصفحة
        return response()->json($product);
    }
    public function storeSpareRequest(Request $request, $productId)
    {
        // 1. التحقق من وجود قطع مختارة
        if (!$request->has('selected_parts')) {
            return redirect()->back()->with('error', 'Please select at least one part.');
        }

        // 2. الحصول على صاحب المنتج من خلال الرسالة (كما اتفقنا سابقاً)
        $message = \App\Models\Message::where('worker_name', auth()->user()->name)
            ->where('status', 'Accepted')
            ->where('subject', 'LIKE', '%' . \App\Models\Product::find($productId)->sn . '%')
            ->first();
        $customerId = $message ? $message->user_id : null;

        $orderItems = [];
        $totalSubtotal = 0;

        // 3. معالجة القطع وجلب أسعارها من جدول spare_parts
        foreach ($request->selected_parts as $partId) {
            $part = \App\Models\SparePart::find($partId);
            if ($part) {
                $quantity = $request->quantities[$partId] ?? 1;
                $unitPrice = $part->price; // جلب سعر القطعة الواحدة من جدول spare_parts

                $orderItems[] = [
                    'id' => $part->id,
                    'name' => $part->name,
                    'image' => $part->image,
                    'quantity' => $quantity,
                    'price' => $unitPrice, // تخزين سعر القطعة وقت الطلب
                ];

                // إضافة سعر هذه القطع للمجموع الكلي (HT)
                $totalSubtotal += ($unitPrice * $quantity);
            }
        }

        // 4. الحسابات الضريبية (VAT 19%)
        $vatRate = 19.00;
        $totalTTC = $totalSubtotal * (1 + ($vatRate / 100));

        // 5. إنشاء الطلب في جدول spare_part_orders
        \App\Models\SparePartOrder::create([
            'worker_id'  => auth()->id(),
            'user_id'    => $customerId,
            'product_id' => $productId,
            'items'      => json_encode($orderItems),
            'subtotal'   => $totalSubtotal, // المبلغ الصافي
            'vat_rate'   => $vatRate,      // الضريبة
            'total_ttc'  => $totalTTC,      // الإجمالي النهائي
            'status'     => 'pending',
        ]);

        return redirect()->route('worker.dashboard')->with('success', 'Order created with current prices.');
    }
    public function showExitVoucher()
    {
        // 1. جلب طلبات "هذا العامل" فقط التي جهزها السبلاي
        $order = SparePartOrder::where('worker_id', auth()->id())
            ->where('status', 'prepared')
            ->latest()
            ->first();

        // 2. بدلاً من الطرد (Redirect)، اسمح له بدخول الصفحة حتى لو كانت فارغة
        // لكي يرى العامل صفحة الوصل ويظهر له تنبيه "لا يوجد طلبات" داخل الصفحة
        return view('worker.exit_voucher', compact('order'));
    }


    public function downloadExitVoucher($id)
    {
        $order = \App\Models\SparePartOrder::with('product')->findOrFail($id);

        // فك تشفير القطع (JSON to Array)
        $items = is_array($order->items) ? $order->items : json_decode($order->items, true);

        $pdf = Pdf::loadView('worker.exit_voucher_pdf', compact('order', 'items'));

        // ضبط حجم الورقة ليكون صغيراً (مثل الوصل) أو A4
        return $pdf->download('Exit_Voucher_Job_' . $order->id . '.pdf');
    }
}
