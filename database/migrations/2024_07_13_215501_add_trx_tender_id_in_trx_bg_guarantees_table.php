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
        Schema::table('trx_tender_bg_guarantees', function (Blueprint $table) {
            $table->unsignedBigInteger('trx_tender_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_tender_bg_guarantees', function (Blueprint $table) {
            $table->dropColumn('trx_tender_id');
            //
        });
    }
};
