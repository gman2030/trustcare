<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SparePartOrder;
use App\Notifications\NewOrderNotification;
use App\Models\User;

class AdminOrderController extends Controller
{
    public function index()
    {
        // جلب الطلبات مع بيانات العامل والمنتج وترتيبها من الأحدث
        $orders = SparePartOrder::with(['worker', 'product'])->latest()->get();
        $orders = SparePartOrder::where('status', 'pending')
            ->latest()
            ->get();
        return view('admin.orders.index', compact('orders'));
    }
    public function store(Request $request)
    {
        // ... كود حفظ الطلبية ...
        $order = SparePartOrder::create([
            'worker_id'  => auth()->id(),
            'product_id' => $request->product_id,
            'items'      => json_encode($request->items),
            'status'     => 'pending',
        ]);

        // جلب المستخدم الذي يملك صلاحية الآدمن
        $admin = User::where('role', 'admin')->first();

        // إرسال الإشعار
        $admin->notify(new NewOrderNotification($order));
    }
    public function acceptOrder(Request $request, $id)
    {
        // 1. جلب الطلب
        $order = \App\Models\SparePartOrder::findOrFail($id);

        // 2. تحديث الحالة الأساسية
        $order->status = 'accepted';

        // 3. التحقق من الـ Checkbox (الحل المضمون)
        // إذا كان الـ checkbox موجوداً في الـ request، اجعل القيمة 1، وإلا 0
        if ($request->has('is_warranty')) {
            $order->is_warranty = 1;
        } else {
            $order->is_warranty = 0;
        }

        // 4. الحفظ والتأكد
        $order->save();

        return back()->with('success', 'تم قبول الطلب وتحويله للمخزن بنجاح!');
    }
}
