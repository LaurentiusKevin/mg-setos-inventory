<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivingOrderInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receiving_order_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('purchase_order_info_id');
            $table->string('invoice_number');
            $table->text('supplier_invoice_number')->comment('kode invoice dari nota supplier');
            $table->float('total_price',12,2);
            $table->text('catatan')->nullable();
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
        Schema::dropIfExists('receiving_order_infos');
    }
}
