<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class HomeController extends Controller
{
    public function index()
    {

        $messages = Message::where('user_id', Auth::id())->latest()->get();
        return view('home', compact('messages'));
    }
    public function searchProduct($sn)
    {
        $product = Product::where('serial_number', $sn)->first();
        return response()->json([
            'success' => $product ? true : false,
            'product' => $product ? [
                'name' => $product->name,
                'image' => asset('uploads/products/' . $product->image)
            ] : null
        ]);
    }


    public function sendMessage(Request $request)
    {
        // التحقق من الحقول
        $request->validate([
            'warranty_image' => 'required|image|mimes:jpeg,png,jpg,jfif|max:10240', // زدنا الحجم لـ 10 ميجا
            'serial_number'  => 'required|string',
            'content'        => 'required|min:3',
        ]);

        $path = null;
        if ($request->hasFile('warranty_image')) {
            // تخزين الصورة باسم فريد في مجلد warranties
            $path = $request->file('warranty_image')->store('warranties', 'public');
        }

        // حفظ في قاعدة البيانات
        Message::create([
            'user_id'        => auth()->id(),
            'subject'        => "Maintenance: " . $request->serial_number,
            'content'        => $request->content,
            'warranty_image' => $path, // تأكد أن الاسم يطابق اسم العمود في الجدول
            'status'         => 'pending',
        ]);

        return back()->with('success', 'Your request has been successfully submitted !');
    }

    public function viewWarrantyCard($id)
    {
        $message = Message::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (!$message->warranty_image) {
            return redirect()->route('home')->with('error', 'Warranty card image not found.');
        }

        $storedPath = trim($message->warranty_image);
        $normalizedPath = ltrim(str_replace('\\', '/', $storedPath), '/');

        // If DB contains a full URL, extract only the path part first.
        if (Str::startsWith($normalizedPath, ['http://', 'https://'])) {
            $urlPath = parse_url($normalizedPath, PHP_URL_PATH);
            $normalizedPath = ltrim((string) $urlPath, '/');
        }

        $normalizedPath = preg_replace('#^(public/)?storage/#', '', $normalizedPath);
        $fileName = basename($normalizedPath);

        $possiblePaths = [
            storage_path('app/public/' . $normalizedPath),
            storage_path('app/public/' . ltrim($storedPath, '/\\')),
            storage_path('app/public/warranties/' . $fileName),
            public_path('storage/' . $normalizedPath),
            public_path('storage/warranties/' . $fileName),
            public_path($normalizedPath),
        ];

        foreach ($possiblePaths as $fullPath) {
            if (File::exists($fullPath)) {
                return response()->file($fullPath);
            }
        }

        return redirect()->route('home')->with('error', 'Warranty card image not found.');
    }
    // ... داخل كلاس HomeController
    public function showSolutions()
    {
        // جلب آخر طلب يخص الزبون الحالي
        // الشروط:
        // 1. يخص المستخدم المسجل (user_id)
        // 2. داخل فترة الضمان (is_warranty = 1)
        // 3. الحالة إما 'proposed_solutions' أو تم رفضها 'rejected' من السبلاي تشان
        $order = \App\Models\SparePartOrder::where('user_id', auth()->id())
            ->where('is_warranty', 1)
            ->whereIn('status', ['proposed_solutions', 'rejected'])
            ->latest()
            ->with('product') // لجلب بيانات المنتج المرتبط
            ->first();

        // إذا لم يجد النظام طلباً تنطبق عليه الشروط، يعيد المستخدم للخلف مع رسالة
        if (!$order) {
            return redirect()->route('home')->with('info', 'No warranty solutions available at this time.');
        }

        // عرض الصفحة وإرسال بيانات الطلب لها
        return view('Proposedsolutions', compact('order'));
    }

    public function showInvoice()
    {
        $order = \App\Models\SparePartOrder::where('user_id', auth()->id())
            ->where('status', 'prepared')
            ->where('is_warranty', 0)
            ->with('product') // أضفنا التحميل المسبق للمنتج
            ->latest()
            ->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'No pending invoices found.');
        }

        // معالجة آمنة للـ JSON
        $items = is_array($order->items) ? $order->items : json_decode($order->items, true);

        return view('thebill', compact('order', 'items'));
    }

    public function downloadPDF($id)
    {
        // أضفنا with('product') لضمان ظهور الرقم التسلسلي في الـ PDF
        $order = \App\Models\SparePartOrder::with('product')->findOrFail($id);

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $items = is_array($order->items) ? $order->items : json_decode($order->items, true);

        $pdf = Pdf::loadView('thebill_pdf', compact('order', 'items'));

        return $pdf->download('Invoice_#' . $order->id . '.pdf');
    }
}
