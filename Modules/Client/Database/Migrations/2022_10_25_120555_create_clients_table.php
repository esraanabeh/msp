<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('phone_number');
            $table->string('email');
            $table->text('address')->nullable();
            $table->integer('number_of_employees')->nullable();
            $table->json('additional_questions')->nullable();
            $table->enum('status',['Active','Pending','Prospect','Declined'])->default('Prospect');
            $table->string('progress')->nullable();

            $table->foreignId('organization_id')->constrained('organizations','id');
            $table->unique(['organization_id','email','phone_number']);

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
        Schema::dropIfExists('clients');
    }
}
