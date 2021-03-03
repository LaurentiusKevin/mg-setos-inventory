<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_menus', function (Blueprint $table) {
            $table->id();
            $table->integer('roles_id');
            $table->integer('sys_menus_id');
            $table->integer('view')->default(0)->comment('0: not allowed, 1: allowed');
            $table->integer('create')->default(0)->comment('0: not allowed, 1: allowed');
            $table->integer('edit')->default(0)->comment('0: not allowed, 1: allowed');
            $table->integer('delete')->default(0)->comment('0: not allowed, 1: allowed');
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
        Schema::dropIfExists('role_menus');
    }
}
