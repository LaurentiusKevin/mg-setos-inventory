<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_infos', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->integer('supplier_id');
            $table->integer('total_item');
            $table->integer('received_item');
            $table->float('total_price',12,0);
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->dateTime('receive_completed_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order_infos');
    }
}
