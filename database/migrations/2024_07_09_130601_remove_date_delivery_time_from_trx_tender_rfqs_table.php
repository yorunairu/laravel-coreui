<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('trx_tender_rfqs', function (Blueprint $table) {
            $table->dropColumn('date_delivery_time');
        });
        Schema::table('trx_tender_rfq_principles', function (Blueprint $table) {
            $table->string('price')->nullable()->after('date_delivery');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('trx_tender_rfqs', function (Blueprint $table) {
            $table->date('date_delivery_time')->nullable();
        });
        Schema::table('trx_tender_rfq_principles', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
