<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQtyColumnToClientMrrServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_MRR_services', function (Blueprint $table) {
            $table->integer('qty')->after('service_id');
            $table->double('cost')->after('qty');
            $table->double('total_amount')->after('cost');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_MRR_services', function (Blueprint $table) {
            $table->dropColumn(['qty','cost','total_amount']);
        });
    }
}
