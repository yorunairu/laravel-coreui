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
        Schema::table('m_currencies', function (Blueprint $table) {
            $table->string('price_rate')->nullable();
            $table->string('date_rate')->nullable();
        });
        
        Schema::table('trx_tender_material_prices', function (Blueprint $table) {
            $table->string('price_rate')->nullable();
            $table->string('date_rate')->nullable();
            $table->string('total_idr_convert')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_currencies', function (Blueprint $table) {
            $table->dropColumn('price_rate');
            $table->dropColumn('date_rate');
        });
        Schema::table('trx_tender_material_prices', function (Blueprint $table) {
            $table->dropColumn('price_rate');
            $table->dropColumn('date_rate');
            $table->dropColumn('total_idr_convert');
        });
    }
};
