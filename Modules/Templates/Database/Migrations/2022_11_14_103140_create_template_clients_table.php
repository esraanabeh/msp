<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_clients', function (Blueprint $table) {
            $table->id();

            $table->foreignId('template_id')
            ->constrained('templates')
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
        Schema::dropIfExists('template_clients');
    }
}
