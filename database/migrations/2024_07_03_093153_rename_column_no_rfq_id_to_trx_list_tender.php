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
        Schema::table('trx_list_tender_detail', function (Blueprint $table) {
            $table->renameColumn('no_rfq_id', 'trx_list_tender_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_list_tender_detail', function (Blueprint $table) {
            $table->renameColumn('trx_list_tender_id', 'no_rfq_id');
        });
    }
};
