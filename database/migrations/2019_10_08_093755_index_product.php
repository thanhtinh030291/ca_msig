<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndexProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product', function (Blueprint $table) {
            DB::statement('ALTER TABLE product ADD FULLTEXT `name` (`name`)'); 
            DB::statement('ALTER TABLE product ENGINE = MyISAM'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product', function (Blueprint $table) {
            DB::statement('ALTER TABLE product DROP INDEX name');
        });
    }
}
