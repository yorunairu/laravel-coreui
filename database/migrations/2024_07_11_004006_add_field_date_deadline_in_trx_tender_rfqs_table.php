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
            $table->date('date_deadline')->nullable()->after('date_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_tender_rfqs', function (Blueprint $table) {
            $table->dropColumn('date_deadline');
        });
    }
};
