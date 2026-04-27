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
        Schema::table('spare_part_orders', function (Blueprint $table) {
            // إضافة عمود بولياني (0 أو 1) وقيمته الافتراضية 0 (ليس تحت الضمان)
            $table->boolean('is_warranty')->default(0)->after('status');
        });
    }

    public function down()
    {
        Schema::table('spare_part_orders', function (Blueprint $table) {
            $table->dropColumn('is_warranty');
        });
    }
};
