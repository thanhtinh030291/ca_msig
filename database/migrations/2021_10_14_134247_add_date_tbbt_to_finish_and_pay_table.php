<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateTbbtToFinishAndPayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('finish_and_pay', function (Blueprint $table) {
            //
            $table->dateTime('date_tbbt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('finish_and_pay', function (Blueprint $table) {
            //
            $table->dropColumn('date_tbbt');
        });
    }
}
