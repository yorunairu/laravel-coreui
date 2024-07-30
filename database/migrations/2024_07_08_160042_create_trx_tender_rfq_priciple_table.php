<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrxTenderRfqPricipleTable extends Migration
{
    public function up()
    {
        Schema::create('trx_tender_rfq_priciples', function (Blueprint $table) {
            $table->id();
            $table->string('principle_id');
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trx_tender_rfq_priciples');
    }
}
