<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayTimeToFinishAndPayTable extends Migration
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
            $table->integer('pay_time')->default('1');
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
            $table->dropColumn('pay_time');
        });
    }
}
