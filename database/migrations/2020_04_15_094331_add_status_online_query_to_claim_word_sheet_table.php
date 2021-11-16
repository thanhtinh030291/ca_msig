<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusOnlineQueryToClaimWordSheetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('claim_word_sheet', function (Blueprint $table) {
            //
            $table->text('status_online_query')->nullable();
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
            //
            $table->dropColumn('status_online_query');
        });
    }
}
