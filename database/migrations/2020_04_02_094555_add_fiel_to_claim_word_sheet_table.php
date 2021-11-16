<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFielToClaimWordSheetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('claim_word_sheet', function (Blueprint $table) {
            $table->integer('30_day')->default('0');
            $table->integer('1_year')->default('0');
            $table->integer('contract_rule')->default('0');
            $table->longText('type_of_visit')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('claim_word_sheet', function (Blueprint $table) {
            $table->dropColumn('30_day');
            $table->dropColumn('1_year');
            $table->dropColumn('contract_rule');
            $table->dropColumn('type_of_visit');
        });
    }
}
