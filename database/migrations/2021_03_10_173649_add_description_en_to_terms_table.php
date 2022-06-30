<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionEnToTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('terms', function (Blueprint $table) {
            $table->text('description_en')->nullable();
        });

        Schema::table('reason_reject', function (Blueprint $table) {
            $table->text('template_en')->nullable();
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('terms', function (Blueprint $table) {
            //
            $table->dropColumn('description_en');
        });

        Schema::table('terms', function (Blueprint $table) {
            //
            $table->dropColumn('template_en');
        });
    }
}
