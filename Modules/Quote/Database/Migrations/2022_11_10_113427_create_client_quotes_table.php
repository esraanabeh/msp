<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_quotes', function (Blueprint $table) {
            $table->id();
            $table->text('introduction');
            $table->json('services');
            $table->unsignedBigInteger('master_service_agreement_id');
            $table->json('other_sections');
            $table->foreignId('client_id')->constrained('clients','id');
            $table->foreignId('organization_id')->constrained('organizations','id');
            $table->boolean('is_sent')->default(0);
            $table->string('status')->nullable();
            $table->foreignId('creator_user_id')->constrained('users','id');
            $table->foreignId('editor_user_id')->constrained('users','id');
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
        Schema::dropIfExists('client_quotes');
    }
}
