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
            $table->unsignedBigInteger('tender_id')->after('id');
            $table->text('doc_evidance')->nullable()->after('description');
            $table->date('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_tender_sales_activities', function (Blueprint $table) {
            //
        });
    }
};
