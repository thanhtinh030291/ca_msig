<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiagnosisToHospitalRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital_request', function (Blueprint $table) {
            $table->text('diagnosis')->nullable();
            $table->text('incur_to')->nullable();
            $table->text('incur_from')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospital_request', function (Blueprint $table) {
            $table->dropColumn('incur_time');
            $table->dropColumn('incur_to');
            $table->dropColumn('incur_from');
        });
    }
}
