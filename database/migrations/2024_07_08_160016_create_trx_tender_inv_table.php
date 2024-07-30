<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrxTenderInvTable extends Migration
{
    public function up()
    {
        Schema::create('trx_tender_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable();
            $table->timestamp('date_created')->nullable();
            $table->string('principle_name')->nullable();
            $table->string('doc_payment_to_principle')->nullable();
            $table->string('doc_inv_from_principle')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trx_tender_invoices');
    }
}
