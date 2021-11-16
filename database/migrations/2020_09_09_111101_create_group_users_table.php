<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGroupUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->integer('lead')->nullable();
            $table->integer('active_leader')->default('1');
            $table->integer('supper')->nullable();
            $table->integer('active_supper')->default('1');
            $table->integer('assistant_manager')->nullable();
            $table->integer('active_assistant_manager')->default('1');
            $table->integer('manager')->nullable();
            $table->integer('active_manager')->default('1');
            $table->integer('header')->nullable();
            $table->integer('active_header')->default('1');
            
            $table->integer('created_user')->nullable();
            $table->integer('updated_user')->nullable();
            $table->integer('is_deleted')->default('0');
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
        Schema::drop('group_users');
    }
}
