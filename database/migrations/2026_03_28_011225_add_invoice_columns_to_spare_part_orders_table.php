<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('spare_part_orders', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->default(0)->after('items');
            $table->decimal('vat_rate', 5, 2)->default(19.00)->after('subtotal');
            $table->decimal('total_ttc', 10, 2)->default(0)->after('vat_rate');
            $table->string('payment_status')->default('Unpaid')->after('total_ttc');
        });
    }

    public function down()
    {
        Schema::table('spare_part_orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'vat_rate', 'total_ttc', 'payment_status']);
        });
    }
};
