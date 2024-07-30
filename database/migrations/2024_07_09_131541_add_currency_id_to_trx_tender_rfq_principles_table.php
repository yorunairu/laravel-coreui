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
            $table->unsignedBigInteger('m_currency_id')->nullable()->after('date_delivery');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_tender_rfq_principles', function (Blueprint $table) {
            $table->dropColumn('m_currency_id');
        });
    }
};
