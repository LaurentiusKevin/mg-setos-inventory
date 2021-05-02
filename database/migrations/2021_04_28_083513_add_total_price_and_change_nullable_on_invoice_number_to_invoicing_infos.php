<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalPriceAndChangeNullableOnInvoiceNumberToInvoicingInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoicing_infos', function (Blueprint $table) {
            $table->float('total_price',12,2)->after('total_item');
            $table->string('invoice_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoicing_infos', function (Blueprint $table) {
            //
        });
    }
}
