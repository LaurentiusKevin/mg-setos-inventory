<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceAndUserIdToInvoicingProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoicing_products', function (Blueprint $table) {
            $table->float('price',12,2)->after('quantity');
            $table->integer('user_id')->after('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoicing_products', function (Blueprint $table) {
            //
        });
    }
}
