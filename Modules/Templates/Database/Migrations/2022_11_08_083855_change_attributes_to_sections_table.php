<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAttributesToSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->string('description')->nullable()->change();
            $table->string('due_date')->nullable()->change();
            $table->string('is_completed')->nullable()->change();
            $table->string('automatic_reminder')->nullable()->change();
            $table->string('reminder_time')->nullable()->change();
            $table->string('reminder_day')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
