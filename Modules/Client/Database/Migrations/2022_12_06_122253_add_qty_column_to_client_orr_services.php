<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQtyColumnToClientOrrServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_ORR_services', function (Blueprint $table) {
            $table->integer('qty')->after('service_id');
            $table->double('total_amount')->after('qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_ORR_services', function (Blueprint $table) {
            $table->dropColumn(['qty','total_amount']);
        });
    }
}
