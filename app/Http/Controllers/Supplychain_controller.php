<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SparePart;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\SparePartOrder;

class Supplychain_controller extends Controller
{

    public function index(Request $request) // أضفنا Request هنا لاستلام بيانات البحث
    {
        // التأكد من الصلاحيات كما في كودك الأصلي
        if (trim(Auth::user()->role) !== 'supply') {
            abort(403, 'Your role is: "' . Auth::user()->role . '" but we need "supply"');
        }

        // إنشاء الاستعلام
        $query = Product::query();

        // إذا تم إدخال رقم تسلسلي في شريط البحث
        if ($request->filled('search_sn')) {
            $query->where('serial_number', 'LIKE', '%' . $request->search_sn . '%');
        }

        $products = $query->latest()->get();

        return view('supplychain.supply_chain', compact('products'));
    }
    // لفتح صفحة الإضافة
    public function create()
    {
        return view('supplychain.add_product');
    }

    // لحفظ المنتج والعودة للمخزن
    public function store(Request $request)
    {
        // 1. التحقق من صحة البيانات المدخلة
        $request->validate([
            'name' => 'required|max:255',
            'serial_number' => 'required|unique:products,serial_number', // للتأكد من عدم تكرار الرقم التسلسلي
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        // 2. إنشاء كائن جديد من موديل المنتج
        $product = new \App\Models\Product();
        $product->name = $request->name;
        $product->serial_number = $request->serial_number;
        $product->price = 0;    // قيمة افتراضية
        $product->quantity = 0; // قيمة افتراضية

        // 3. معالجة ورفع الصورة إذا وجدت
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $product->image = $imageName;
        }

        // 4. الحفظ في قاعدة البيانات
        $product->save();

        // 5. إعادة التوجيه لجدول المخزون مع رسالة نجاح
        return redirect()->route('supply.dashboard')->with('success', 'Product registered successfully!');
    }

    public function updateStock($id, $action)
    {
        $product = Product::findOrFail($id);

        if ($action == 'increase') {
            $product->increment('quantity');
        } elseif ($action == 'decrease' && $product->quantity > 0) {
            $product->decrement('quantity');
        }

        return back()->with('success', 'Stock updated!');
    }
    public function edit($id)
    {
        $product = Product::with('spareParts')->findOrFail($id);

        // فتح صفحة التعديل (تأكد من اسم الملف edit.blade.php)
        return view('supplychain.edit', compact('product'));
    }
    // دالة تحديث المنتج الأساسي
    public function update(Request $request, $id)
    {
        // 1. جلب المنتج أو إظهار خطأ 404
        $product = Product::findOrFail($id);

        // 2. التحقق من صحة البيانات (Validation)
        $request->validate([
            'name' => 'required|max:255',
            'serial_number' => 'required|unique:products,serial_number,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        // 3. تحديث بيانات المنتج الأساسية
        $product->name = $request->name;
        $product->serial_number = $request->serial_number;

        // معالجة الصورة الجديدة للمنتج إن وجدت
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $product->image = $imageName;
        }

        $product->save();

        // 4. تحديث قطع الغيار (الكمية والسعر) من الجدول دفعة واحدة
        if ($request->has('existing_parts')) {
            foreach ($request->existing_parts as $partId => $data) {
                SparePart::where('id', $partId)->update([
                    'quantity' => $data['quantity'] ?? 0,
                    'price'    => $data['price'] ?? 0,
                ]);
            }
        }

        return redirect()->route('supply.dashboard')->with('success', 'تم حفظ جميع التعديلات بنجاح!');
    }
    // دالة إضافة قطعة غيار (التي أضفتها في ملف الروابط)
    public function storeSparePart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'part_name'  => 'required|max:255',
            'part_image' => 'required|image|max:2048',
            'quantity'   => 'required|integer|min:0', // الحقل موجود بالفعل
            'price'      => 'required|numeric|min:0',   // الحقل الجديد
        ]);

        $part = new \App\Models\SparePart();
        $part->product_id = $request->product_id;
        $part->name       = $request->part_name;
        $part->quantity   = $request->quantity;
        $part->price      = $request->price;

        if ($request->hasFile('part_image')) {
            $imageName = 'part_' . time() . '.' . $request->part_image->extension();
            $request->part_image->move(public_path('uploads/parts'), $imageName);
            $part->image = $imageName;
        }

        $part->save();
        return back()->with('success', 'Component added successfully!');
    }

    // دالة الحذف (لإكمال مساراتك)
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('supply.dashboard')->with('success', 'تم حذف المنتج!');
    }
    public function destroySparePart($id)
    {
        $part = \App\Models\SparePart::findOrFail($id);

        // حذف الصورة من المجلد قبل حذف السجل
        if ($part->image && file_exists(public_path('uploads/parts/' . $part->image))) {
            unlink(public_path('uploads/parts/' . $part->image));
        }

        $part->delete();

        return back()->with('success', 'Component removed successfully!');
    }
    public function show($id)
    {
        $product = Product::with('spareParts')->findOrFail($id);
        $spareParts = $product->spareParts;
        return view('supplychain.show', compact('product', 'spareParts'));
    }
    public function updateSparePart(Request $request, $id)
    {
        $part = \App\Models\SparePart::findOrFail($id);

        // التحقق من البيانات
        $request->validate([
            'quantity' => 'numeric|min:0',
            'price'    => 'numeric|min:0',
        ]);

        // تحديث القيم المرسلة فقط
        $part->update($request->only(['quantity', 'price']));

        return back()->with('success', 'The update was successful.');
    }
    public function bulkUpdate(Request $request)
    {
        foreach ($request->existing_parts as $id => $data) {
            SparePart::where('id', $id)->update([
                'quantity' => $data['quantity'],
                'price'    => $data['price']
            ]);
        }
        return back()->with('success', 'تم تحديث المخزون بنجاح');
    }
    public function receivedRequests()
    {
        // جلب الطلبات التي حالتها 'accepted' فقط
        $orders = SparePartOrder::where('status', 'accepted')
            ->latest()
            ->get();

        return view('supplychain.requests', compact('orders'));
    }
    // دالة تحديث حالة الطلب إلى "جاهز" وإصدار ظرف الخروج
    public function markAsPrepared($id)
    {
        $order = SparePartOrder::findOrFail($id);
        $order->status = 'prepared'; // تأكد أن الكلمة مطابقة تماماً لما في شرط الـ 403
        $order->save();

        return back()->with('success', 'Order is ready for collection!');
    }
    public function rejectBySupply(Request $request, $id)
    {
        $order = \App\Models\SparePartOrder::findOrFail($id);

        // الشرط: إذا كان الأدمن حدد أنه تحت الضمان + السبلاي تشين رفض لعدم التوفر
        if ($order->is_warranty && $request->reason == 'out_of_stock') {
            $order->status = 'proposed_solutions';
            $order->save();

            return redirect()->back()->with('info', 'تم إرسال حلول التعويض للمستخدم بنجاح.');
        }

        $order->status = 'rejected';
        $order->save();
        return redirect()->back()->with('error', 'تم رفض الطلب.');
    }
}
