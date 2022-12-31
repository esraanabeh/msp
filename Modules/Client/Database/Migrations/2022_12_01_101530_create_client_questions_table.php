<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_questions', function (Blueprint $table) {
            $table->id();


            $table->text('question');

            $table->foreignId('client_quote_id')->nullable()
            ->constrained('client_quotes')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreignId('client_id')
            ->constrained('clients')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreignId('organization_id')
            ->constrained('organizations')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreignId('service_id')
            ->constrained('services')
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
        Schema::dropIfExists('client_questions');
     
    }
}
