<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionsClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections_clients', function (Blueprint $table) {
            $table->id();
            $table->date('due_date')->nullable();
            $table->boolean('is_completed')->default(0);
            $table->float('progress')->default(0);
            $table->foreignId('section_id')
            ->constrained('sections')
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
