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
        Schema::create('trx_tender_material_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trx_tender_material_id');
            $table->unsignedBigInteger('m_currency_id');
            $table->string('price')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trx_tender_material_prices');
    }
};
