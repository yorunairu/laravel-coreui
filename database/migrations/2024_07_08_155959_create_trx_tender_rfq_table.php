<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrxTenderRfqTable extends Migration
{
    public function up()
    {
        Schema::create('trx_tender_rfqs', function (Blueprint $table) {
            $table->id();
            $table->string('rfq_no')->unique()->autoIncrement();
            $table->timestamp('date_created')->nullable();
            $table->timestamp('date_delivery_time')->nullable();
            $table->string('doc_quo_from_principle')->nullable();
            $table->timestamps(); // This will add created_at and updated_at columns
            $table->softDeletes(); // This will add deleted_at column
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trx_tender_rfqs');
    }
}
