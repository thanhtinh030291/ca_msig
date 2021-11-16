<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTokenMfileToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->timestamp('updated_token_mfile_at', 0)->nullable();
            $table->timestamp('updated_token_cps_at', 0)->nullable();
            $table->text('token_mfile')->nullable();
        });

        // Schema::table('claim', function (Blueprint $table) {
        //     $table->string('mfile_claim_id')->nullable();
        //     $table->string('mfile_claim_file_id')->nullable();
        //     $table->timestamp('mfile_claim_update_at')->nullable();
        // });    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            
            $table->dropColumn('updated_token_mfile_at');
            $table->dropColumn('updated_token_cps_at');
            $table->dropColumn('token_mfile');
        });

        // Schema::table('claim', function (Blueprint $table) {
        //     $table->dropColumn('mfile_claim_id');
        //     $table->dropColumn('mfile_claim_file_id');
        //     $table->dropColumn('mfile_claim_update_at');
        // });
    }
}
