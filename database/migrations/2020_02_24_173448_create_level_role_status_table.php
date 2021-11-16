<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLevelRoleStatusTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('level_role_status', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->bigInteger('min_amount');
            $table->bigInteger('max_amount');
            $table->integer('begin_status');
            $table->integer('end_status');
            $table->integer('signature_accepted_by')->nullable();
            $table->integer('created_user');
            $table->integer('updated_user');
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
        Schema::drop('level_role_status');
    }
}
