<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->string('title');

            $table->string('options')->nullable();

            $table->enum('type',['text_block',
                            'short_replies','long_replies',
                            'multiple_choice','dorp_down',
                            'documents','date',
                            'single_choice']);

            $table->boolean('is_required')->default(false);

            $table->foreignId('member_id')
            ->constrained('members')
            ->onDelete('cascade')
            ->onUpdate('cascade')->nullable();

            $table->foreignId('section_id')
            ->constrained('sections')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->enum('status',['open',
                            'pending','in_progress',
                            'done']);

            $table->string('answer')->nullable();

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
        Schema::dropIfExists('tasks');
    }
}
