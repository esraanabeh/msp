<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangAttributesInSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->date('due_date')->change();
            $table->boolean('is_completed')->change();
            $table->boolean('automatic_reminder')->change();
            $table->time('reminder_time')->change();
            $table->integer('reminder_day')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->string('due_date')->change();
            $table->string('is_completed')->change();
            $table->string('automatic_reminder')->change();
            $table->string('reminder_time')->change();
            $table->string('reminder_day')->change();
        });
    }
}
