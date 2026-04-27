<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            // إضافة حقل worker_id كفهرس لربطه بجدول المستخدمين
            $table->unsignedBigInteger('worker_id')->nullable()->after('user_id');

            // إعداد علاقة (اختياري ولكن مفضل)
            $table->foreign('worker_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['worker_id']);
            $table->dropColumn('worker_id');
        });
    }
};
