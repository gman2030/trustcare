<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('spare_parts', function (Blueprint $table) {
        $table->id();
        // ربط قطعة الغيار بالمنتج الأساسي (عند حذف المنتج تُحذف قطعه تلقائياً)
        $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
        $table->string('name');
        $table->string('image')->nullable();
        $table->integer('quantity')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_parts');
    }
};
