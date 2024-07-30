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
            $table->unsignedInteger('sequence_number')->default(1)->after('rfq_no');
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
            $table->dropColumn('sequence_number');
        });
    }
};
