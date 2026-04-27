<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\SparePartOrder;
use App\Models\Order; // تأكد من وجود الموديل أو استبداله بـ Message إذا كنت تستخدم جدولا واحدا
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * --- قسم الإدارة العام (General Admin) ---
     */

    // لوحة التحكم الرئيسية وعرض رسائل/شكاوى الزبائن
    public function dashboard()
    {
        $messages = Message::with('user')->latest()->get();

        foreach ($messages as $msg) {
            $parts = explode(': ', $msg->subject);
            $msg->extracted_sn = isset($parts[1]) ? trim($parts[1]) : 'N/A';
        }

        return view('admin.dashboard', compact('messages'));
    }

    // صفحة البداية (Home)
    public function index()
    {
        return view('admin.home');
    }

    /**
     * --- إدارة العمال (Workers Management) ---
     */

    // عرض قائمة العمال والتحكم بهم
    // عرض قائمة العمال والتحكم بهم
    public function showWorkers()
    {
        $workers = User::where('role', 'worker')->get();

        // سنجلب آخر 20 رسالة موجودة في النظام للتأكد من ظهور البيانات أولاً
        $pendingOrders = Message::with('user')->latest()->get();

        return view('admin.workers-control', compact('workers', 'pendingOrders'));
    }

    // فتح صفحة إضافة عامل جديد
    public function createWorker()
    {
        return view('admin.create-worker');
    }

    // حفظ بيانات العامل الجديد
    public function storeWorker(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'worker',
        ]);

        return redirect()->route('admin.workers')->with('success', 'Worker account created successfully!');
    }

    // حذف مستخدم (عامل أو زبون)
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    public function assignTaskForm($worker_id)
    {
        $worker = User::findOrFail($worker_id);

        $pendingOrders = Message::where('status', 'Pending')->get();

        return view('admin.assign-task', compact('worker', 'pendingOrders'));
    }

    // حفظ التعيين وتحديث حالة الشكوى
    public function storeTask(Request $request)
    {
        $request->validate([
            'worker_id' => 'required|exists:users,id',
            'order_id' => 'required|exists:messages,id', // نستخدم جدول المراسلات كما في الكود الخاص بك
        ]);

        $worker = User::find($request->worker_id);
        $message = Message::find($request->order_id);

        if ($message) {
            $message->update([
                'worker_name' => $worker->name,
                'status' => 'Assigned'
            ]);
            return redirect()->route('admin.workers')->with('success', "Task successfully assigned to " . $worker->name);
        }

        return back()->with('error', 'Request not found.');
    }
    public function sparePage()
    {
        // هنا نستخدم with('message.user') لجلب الرسالة والزبون المرتبط بها في استعلام واحد
        // هذا هو السطر الذي يضمن لك وصول اسم الزبون لجدول قطع الغيار
        $spareOrders = SparePartOrder::with('message.user')->latest()->get();

        return view('admin.spare-parts', compact('spareOrders'));
    }

    public function acceptOrder(Request $request, $id)
    {
        $order = SparePartOrder::findOrFail($id);
        $order->status = 'accepted';
        $order->is_warranty = $request->has('is_warranty') ? 1 : 0;
        $order->save();
        return back()->with('success', 'Order sent to supply chain.');
    }

    public function viewWarrantyCard($id)
    {
        $message = Message::findOrFail($id);

        if (!$message->warranty_image) {
            abort(404, 'Warranty card image not found.');
        }

        $storedPath = trim($message->warranty_image);
        $normalizedPath = ltrim(str_replace('\\', '/', $storedPath), '/');

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

        abort(404, 'Warranty card image not found.');
    }
}
