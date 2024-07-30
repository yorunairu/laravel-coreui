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
            $table->renameColumn('satuan', 'm_uom_id');

        });
        Schema::table('trx_list_tender_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('m_uom_id')->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_list_tender_detail', function (Blueprint $table) {
            $table->renameColumn('m_oum_id', 'satuan');
            $table->string('satuan')->change();
        });
    }
};
