<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('template_id')
            ->constrained('templates')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreignId('team_id')
            ->constrained('teams')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->string('title');
            $table->string('description');
            $table->date('due_date');
            $table->boolean('is_completed');
            $table->boolean('automatic_reminder');
            $table->time('reminder_time');
            $table->integer('reminder_day');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sections');
    }
}
