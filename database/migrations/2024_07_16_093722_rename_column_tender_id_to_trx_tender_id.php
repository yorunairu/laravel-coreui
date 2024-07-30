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
        Schema::table('trx_tender_sales_activities', function (Blueprint $table) {
            $table->date('date')->nullable()->change();
            $table->renameColumn('tender_id', 'trx_tender_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_tender_sales_activities', function (Blueprint $table) {
            $table->date('date')->nullable(false)->change();
            $table->renameColumn('trx_tender_id', 'tender_id');
        });
    }
};
