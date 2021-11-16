<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransactionRoleStatusTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_role_status', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('level_role_status_id');
            $table->integer('current_status');
            $table->integer('role');
            $table->integer('to_status');
            $table->integer('is_deleted')->default('0');
            $table->integer('created_user');
            $table->integer('updated_user');
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
        Schema::drop('transaction_role_status');
    }
}
