<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoiceToClaimTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('claim', function (Blueprint $table) {
            $table->string('original_invoice_no',500)->nullable();
            $table->string('original_invoice_type',3)->default('No');
            $table->string('original_invoice_no_not_ready',500)->nullable();

            $table->string('e_invoice_no',500)->nullable();
            $table->string('e_invoice_type',3)->default('No');
            $table->string('e_invoice_no_not_ready',500)->nullable();

            $table->string('converted_invoice_no',500)->nullable();
            $table->string('converted_invoice_type',3)->default('No');
            $table->string('converted_invoice_no_not_ready',500)->nullable();

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
            $table->dropColumn('original_invoice_no');
            $table->dropColumn('original_invoice_type');
            $table->dropColumn('original_invoice_no_not_ready');

            $table->dropColumn('e_invoice_no');
            $table->dropColumn('e_invoice_type');
            $table->dropColumn('e_invoice_no_not_ready');

            $table->dropColumn('converted_invoice_no');
            $table->dropColumn('converted_invoice_type');
            $table->dropColumn('converted_invoice_no_not_ready');
        });
    }
}
