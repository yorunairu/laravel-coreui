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
        Schema::create('trx_list_tender_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('no_rfq_id')->nullable();
            $table->string('material_code')->nullable();
            $table->text('description')->nullable();
            $table->decimal('qty')->nullable();
            $table->string('satuan')->nullable();
            $table->decimal('unit_price_principle')->nullable();
            $table->decimal('unit_price_kz')->nullable();
            $table->decimal('total_amount')->nullable();
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trx_list_tender_detail');
    }
};
