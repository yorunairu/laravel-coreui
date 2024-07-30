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
        Schema::table('trx_tender_pos', function (Blueprint $table) {
            $table->unsignedBigInteger('trx_tender_rfq_id')->after('trx_tender_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_tender_pos', function (Blueprint $table) {
            $table->dropColumn('trx_tender_rfq_id');
        });
    }
};
