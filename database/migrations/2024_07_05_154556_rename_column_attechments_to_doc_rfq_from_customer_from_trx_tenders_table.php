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
        Schema::table('trx_tenders', function (Blueprint $table) {
            $table->renameColumn('attachment', 'doc_rfq_from_customer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_tenders', function (Blueprint $table) {
            $table->renameColumn('doc_rfq_from_customer', 'attachment');
        });
    }
};
