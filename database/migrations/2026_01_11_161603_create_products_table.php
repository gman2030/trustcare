<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up() {
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('serial_number')->unique(); // الرقم التسلسلي
        $table->string('name'); // اسم المنتج
        $table->string('image');
        $table->decimal('price', 10, 2)->default(0); // جديد: لإصلاح خطأ price
        $table->integer('quantity')->default(0);    // جديد: لإصلاح خطأ quantity
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
