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
        Schema::table('trx_tender_rfqs', function (Blueprint $table) {
            $table->unsignedBigInteger('trx_tender_id')->after('id');
        });
        Schema::rename('trx_tender_rfq_priciples', 'trx_tender_rfq_principles');
        Schema::table('trx_tender_rfq_principles', function (Blueprint $table) {
            $table->unsignedBigInteger('trx_tender_rfq_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_tender_rfqs', function (Blueprint $table) {
            $table->dropColumn('trx_tender_id');
        });
        Schema::rename('trx_tender_rfq_principles', 'trx_tender_rfq_priciples');
        Schema::table('trx_tender_rfq_principles', function (Blueprint $table) {
            $table->dropColumn('trx_tender_rfq_id');
        });
    }
};
