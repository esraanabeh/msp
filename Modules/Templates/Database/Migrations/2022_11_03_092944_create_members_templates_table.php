<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members_templates', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('template_id')
            ->constrained('templates')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreignId('member_id')
            ->constrained('members')
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
        Schema::dropIfExists('members_templates');
    }
}
