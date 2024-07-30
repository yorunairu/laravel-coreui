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
        Schema::dropIfExists('trx_tender_pos');
        Schema::dropIfExists('trx_tender_purchase_orders');
        Schema::create('trx_tender_pos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trx_tender_id')->nullable();
            $table->string('customer_po_no')->nullable();
            $table->string('customer_po_date')->nullable();
            $table->string('customer_po_doc')->nullable();
            $table->string('customer_po_note')->nullable();
            $table->string('principle_po_no')->nullable();
            $table->string('principle_po_date')->nullable();
            $table->string('principle_po_doc')->nullable();
            $table->string('principle_po_note')->nullable();
            $table->dateTime('date_po')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
        Schema::create('trx_tender_po_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trx_tender_po_id')->nullable();
            $table->string('term_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
        Schema::create('trx_tender_po_delpoints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trx_tender_po_id')->nullable();
            $table->string('delpoint_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
        Schema::create('trx_tender_quos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trx_tender_id')->nullable();
            $table->string('quo_no')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
        Schema::create('trx_tender_quo_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trx_tender_quo_id')->nullable();
            $table->string('term_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
        Schema::create('trx_tender_quo_delpoints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trx_tender_quo_id')->nullable();
            $table->string('delpoint_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trx_tender_pos');
        Schema::dropIfExists('trx_tender_po_terms');
        Schema::dropIfExists('trx_tender_po_delpoints');
        Schema::dropIfExists('trx_tender_quos');
        Schema::dropIfExists('trx_tender_quo_terms');
        Schema::dropIfExists('trx_tender_quo_delpoints');
    }
};
