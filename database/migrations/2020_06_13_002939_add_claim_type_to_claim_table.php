<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClaimTypeToClaimTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('claim', function (Blueprint $table) {
            $table->char('claim_type')->default('M');
        });
        Schema::table('letter_template', function (Blueprint $table) {
            $table->char('claim_type')->default('M');
        });
        Schema::table('level_role_status', function (Blueprint $table) {
            $table->char('claim_type')->default('M');
        });
        Schema::table('role_change_status', function (Blueprint $table) {
            $table->char('claim_type')->default('M');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('claim', function (Blueprint $table) {
            $table->dropColumn('claim_type');
        });
        Schema::table('letter_template', function (Blueprint $table) {
            $table->dropColumn('claim_type');
        });
        Schema::table('level_role_status', function (Blueprint $table) {
            $table->dropColumn('claim_type');
        });
        Schema::table('role_change_status', function (Blueprint $table) {
            $table->dropColumn('claim_type');
        });
    }
}
