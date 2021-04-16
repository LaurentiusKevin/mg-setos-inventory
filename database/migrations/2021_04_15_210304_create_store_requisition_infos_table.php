<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreRequisitionInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_requisition_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('department_id');
            $table->string('invoice_number')->comment('SR (Store Requisition)');
            $table->text('info_penggunaan');
            $table->integer('total_item');
            $table->float('total_price',12,0);
            $table->text('catatan')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('store_requisition_infos');
    }
}
