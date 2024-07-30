<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrxTenderPoTable extends Migration
{
    public function up()
    {
        Schema::create('trx_tender_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_no')->unique()->autoIncrement();
            $table->timestamp('date_created')->nullable();
            $table->timestamp('date_delivery_time')->nullable();
            $table->string('principle_id')->nullable();
            $table->string('doc_do_from_priciple')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trx_tender_purchase_orders');
    }
}
