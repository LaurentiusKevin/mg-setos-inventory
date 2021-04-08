<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivingOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receiving_order_products', function (Blueprint $table) {
            $table->id();
            $table->integer('receiving_order_info_id');
            $table->integer('purchase_order_info_id');
            $table->integer('purchase_order_product_id');
            $table->integer('product_id');
            $table->integer('quantity');
            $table->float('price', 12,2);
            $table->float('total_price', 12,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receiving_order_products');
    }
}
