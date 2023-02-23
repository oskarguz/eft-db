<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_prices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->decimal('price');
            $table->decimal('price_rub');
            $table->foreignUuid('item_id')->constrained('items');
            $table->foreignUuid('vendor_id')->constrained('vendors');
            $table->foreignUuid('currency_id')->constrained('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items_prices');
    }
};
