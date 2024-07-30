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
            $table->string('quo_price_review')->nullable()->after('doc_quo_from_prinsiple');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_tenders', function (Blueprint $table) {
            $table->dropColumn('quo_price_review');
        });
    }
};
