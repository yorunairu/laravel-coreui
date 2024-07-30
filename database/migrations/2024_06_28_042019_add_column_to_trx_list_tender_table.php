<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    //status default draftt
    //no_rfq
    //tanggal_keluar

    public function up(): void
    {
        Schema::table('trx_list_tender', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('position');
            $table->string('no_rfq')->after('position')->nullable();
            $table->datetime('tanggal_keluar')->after('position')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_list_tender', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('no_rfq');
            $table->dropColumn('tanggal_keluar');
        });
    }
};
