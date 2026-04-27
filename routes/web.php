<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Supplychain_controller;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\viewcontroller;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\WorkerProductController;

Route::get('/login-sign-up', [AuthController::class, 'showForm'])->name('login.view');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/user/search-product/{sn}', [HomeController::class, 'searchProduct']);
Route::get('/worker/search-product/{sn}', [SearchController::class, 'searchBySerialNumber']);


Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/send-message', [HomeController::class, 'sendMessage'])->name('send.message');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/home', [AdminController::class, 'dashboard'])->name('admin.home');
    Route::get('/warranty-card/{id}', [AdminController::class, 'viewWarrantyCard'])->name('admin.warranty.view');
    Route::get('/spare-parts', [AdminController::class, 'sparePage'])->name('admin.spare');
    Route::get('/workers-control', [AdminController::class, 'showWorkers'])->name('admin.workers');
    Route::post('/assign-task/store', [AdminController::class, 'storeTask'])->name('admin.tasks.store');
    Route::delete('/user/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.user.delete');
    Route::post('/products/store', [AdminController::class, 'storeProduct'])->name('admin.product.store');
});

Route::middleware(['auth'])->prefix('worker')->group(function () {
    Route::get('/dashboard', [WorkerController::class, 'workerDashboard'])->name('worker.dashboard');
    Route::get('/spare-parts', [WorkerController::class, 'sparePage'])->name('worker.spare');
    Route::post('/accept-task/{id}', [WorkerController::class, 'acceptTask'])->name('worker.accept');
    Route::post('/complete-task/{id}', [WorkerController::class, 'completeTask'])->name('worker.complete');
    Route::post('/delete-task/{id}', [WorkerController::class, 'destroyTask'])->name('worker.destroy');
    Route::post('/update-parts/{id}', [WorkerController::class, 'updateParts'])->name('worker.update.parts');
});


Route::middleware(['auth'])->prefix('supply-chain')->group(function () {

    Route::get('/dashboard', [Supplychain_controller::class, 'index'])->name('supply.dashboard');


    Route::get('/create', [Supplychain_controller::class, 'create'])->name('supply.create');
    Route::post('/store', [Supplychain_controller::class, 'store'])->name('supply.store');


    Route::get('/edit/{id}', [Supplychain_controller::class, 'edit'])->name('supply.edit');
    Route::put('/update/{id}', [Supplychain_controller::class, 'update'])->name('supply.update');
    Route::delete('/spare-parts/destroy/{id}', [Supplychain_controller::class, 'destroySparePart'])->name('spare-parts.destroy');
    Route::post('/spare-parts/store', [Supplychain_controller::class, 'storeSparePart'])->name('spare-parts.store');


    Route::delete('/destroy/{id}', [Supplychain_controller::class, 'destroy'])->name('supply.destroy');

    Route::post('/update-stock/{id}/{action}', [Supplychain_controller::class, 'updateStock'])->name('supply.updateStock');

    Route::get('/show/{id}', [Supplychain_controller::class, 'show'])->name('supply.show');
});


Route::get('/setup-admin', function () {
    \App\Models\User::updateOrCreate(['email' => 'admin.tc@gmail.com'], ['name' => 'Super Admin', 'password' => Hash::make('admin123'), 'role' => 'admin', 'phone' => '00000000']);
    return "Admin Created Successfully!";
});
Route::put('/spare-parts/bulk-update', [Supplychain_controller::class, 'bulkUpdate'])->name('spare-parts.bulkUpdate');
Route::get('/worker/product-view/{id}', [viewcontroller::class, 'show'])->name('worker.product.view');
Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
Route::post('/worker/product/{id}/store', [WorkerController::class, 'storeSpareRequest'])->name('worker.spare.confirm');
Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
Route::post('/admin/orders/{id}/accept', [AdminOrderController::class, 'acceptOrder'])->name('admin.orders.accept');
Route::get('/supplychain/requests', [Supplychain_controller::class, 'receivedRequests'])->name('supplychain.requests');
Route::get('/', function () {
    return view('n1page');
});

Route::get('/worker/exit-voucher', [WorkerController::class, 'showExitVoucher'])->name('exit.voucher');
Route::post('/worker/confirm-exit', [WorkerController::class, 'confirmExit'])->name('worker.confirm.exit');
Route::post('/supply-chain/prepare/{id}', [Supplychain_controller::class, 'markAsPrepared'])->name('supply.prepare');
Route::middleware(['auth'])->group(function () {

    Route::get('/my-replacement-solutions', [HomeController::class, 'showSolutions'])->name('user.solutions');
});
Route::get('/proposed-solutions', [App\Http\Controllers\HomeController::class, 'showSolutions'])->name('Proposedsolutions');
Route::post('/supply-chain/reject/{id}', [Supplychain_controller::class, 'rejectBySupply'])->name('supply.reject');
Route::middleware(['auth'])->group(function () {
    Route::get('/my-invoice', [HomeController::class, 'showInvoice'])->name('thebill');
    Route::get('/user/warranty-card/{id}', [HomeController::class, 'viewWarrantyCard'])->name('user.warranty.view');
});
Route::get('/invoice/download/{id}', [HomeController::class, 'downloadPDF'])->name('invoice.download');
Route::get('/worker/exit-voucher/download/{id}', [WorkerController::class, 'downloadExitVoucher'])->name('worker.exit.download');
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/workers/create', [AdminController::class, 'createWorker'])->name('admin.workers.create');
    Route::post('/workers/store', [AdminController::class, 'storeWorker'])->name('admin.workers.store');
});
// تأكد أن المسار مكتوب بهذا الشكل
Route::middleware(['auth'])->group(function () {

    // مسار عرض العمال
    Route::get('/admin/workers', [AdminController::class, 'showWorkers'])->name('admin.workers');


});
