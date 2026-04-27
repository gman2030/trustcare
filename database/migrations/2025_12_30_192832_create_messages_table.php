<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->text('content');
            $table->string('status')->default('pending'); // تم التغيير للإنجليزية لسهولة التعامل برمجياً
            $table->string('worker_name')->nullable();    // اسم عامل الصيانة
            $table->text('admin_reply')->nullable();      // الحقل الجديد لرد الأدمن
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};