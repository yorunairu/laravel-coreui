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
        Schema::rename('trx_list_tender_detail', 'trx_tender_material');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('trx_tender_material', 'trx_list_tender_detail');
    }
};