<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks_clients', function (Blueprint $table) {
            $table->id();
            $table->enum('status',['open',
            'pending','in_progress',
            'done'])->default('open');
            $table->string('answer')->nullable();
            $table->boolean('done')->default(false);
            $table->foreignId('task_id')
            ->constrained('tasks')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->foreignId('client_id')
            ->constrained('clients')
            ->onDelete('cascade')
            ->onUpdate('cascade');
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
