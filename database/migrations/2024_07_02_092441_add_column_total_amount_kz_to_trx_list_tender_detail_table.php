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
            $table->string('total_amount_principle')->after('unit_price_principle')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_list_tender_detail', function (Blueprint $table) {
            $table->dropColumn('total_amount_principle');
        });
    }
};
