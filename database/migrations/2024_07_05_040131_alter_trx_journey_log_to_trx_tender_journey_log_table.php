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
        Schema::rename('trx_journey_log', 'trx_tender_journey_log');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('trx_tender_journey_log', 'trx_journey_log');
    }
};