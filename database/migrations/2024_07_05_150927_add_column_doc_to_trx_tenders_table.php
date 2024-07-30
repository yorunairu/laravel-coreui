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
            $table->text('doc_quo_from_prinsiple')->nullable()->after('currency_id');
            $table->text('doc_bg_penawaran')->nullable()->after('currency_id');
            $table->text('doc_bg_performa_bond')->nullable()->after('currency_id');
            $table->text('doc_do_from_priciple')->nullable()->after('currency_id');
            $table->text('doc_payment_to_principle')->nullable()->after('currency_id');
            $table->text('doc_payment_from_customer')->nullable()->after('currency_id');
            $table->string('bg_performa_bond')->nullable()->after('currency_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_tenders', function (Blueprint $table) {
            $table->dropColumn('doc_quo_from_prinsiple');
            $table->dropColumn('doc_bg_penawaran');
            $table->dropColumn('doc_bg_performa_bond');
            $table->dropColumn('doc_do_from_priciple');
            $table->dropColumn('doc_payment_to_principle');
            $table->dropColumn('doc_payment_from_customer');
            $table->dropColumn('bg_performa_bond');
        });
    }
};
