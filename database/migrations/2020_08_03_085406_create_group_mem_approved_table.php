<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupMemApprovedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_mem_approved', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('leader')->nullable();
            $table->string('active_leader')->default('1'); 
            $table->string('super')->nullable();
            $table->string('active_super')->default('1'); 
            $table->string('qc')->nullable();
            $table->string('active_qc')->default('1'); 
            $table->string('assistant')->nullable();
            $table->string('active_assistant')->default('1'); 
            $table->string('manager')->nullable();
            $table->string('active_manager')->default('1'); 
            $table->string('header')->nullable();
            $table->string('active_header')->default('1'); 
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
        Schema::dropIfExists('group_mem_approved');
    }
}
