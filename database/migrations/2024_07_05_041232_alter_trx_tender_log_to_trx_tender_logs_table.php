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
        Schema::rename('trx_tender_log', 'trx_tender_logs');
        Schema::rename('trx_tender', 'trx_tenders');
        Schema::rename('trx_tender_material', 'trx_tender_materials');
        Schema::rename('m_currency', 'm_currencies');
        Schema::rename('m_material', 'm_materials');
        Schema::rename('m_uom', 'm_uoms');
        Schema::rename('sales_activity', 'trx_tender_sales_activities');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('trx_tender_logs', 'trx_tender_log');
        Schema::rename('trx_tenders', 'trx_tender');
        Schema::rename('trx_tender_materials', 'trx_tender_material');
        Schema::rename('m_currencies', 'm_currency');
        Schema::rename('m_materials', 'm_material');
        Schema::rename('m_uoms', 'm_uom');
        Schema::rename('trx_tender_sales_activities', 'sales_activity');
    }
};