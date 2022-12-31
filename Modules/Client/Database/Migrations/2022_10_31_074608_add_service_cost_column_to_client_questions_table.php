<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServiceCostColumnToClientQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_questions', function (Blueprint $table) {
            $table->text('opportunity_notes')->nullable()->after('is_opportunity');
            $table->double('service_cost')->nullable()->after('opportunity_notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_questions', function (Blueprint $table) {
            $table->dropColumn(['service_cost','opportunity_notes']);
        });
    }
}
