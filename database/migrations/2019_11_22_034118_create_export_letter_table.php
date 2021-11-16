<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportLetterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_letter', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('claim_id')->nullable();
            $table->integer('letter_template_id')->nullable();
            $table->integer('status')->default('0');
            $table->longText('approve')->nullable();
            $table->longText('wait')->nullable();
            $table->longText('note')->nullable();
            $table->integer('created_user');
            $table->integer('updated_user');
            $table->timestamps();
            $table->integer('is_deleted')->default('0');
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
        Schema::dropIfExists('export_letter');
    }
}
