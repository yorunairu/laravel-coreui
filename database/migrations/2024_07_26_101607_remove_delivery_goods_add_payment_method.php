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
        Schema::table('trx_tender_rfq_principles', function (Blueprint $table) {
            $table->dropColumn('delivery_goods_days');
            $table->unsignedBigInteger('payment_method')->after('date_delivery')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_tender_rfq_principles', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
};
