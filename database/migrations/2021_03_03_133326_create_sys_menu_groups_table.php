<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysMenuGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_menu_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->string('segment_name',50);
            $table->string('icon',50);
            $table->integer('ord');
            $table->boolean('is_private')->default(0);
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
        Schema::dropIfExists('sys_menu_groups');
    }
}
