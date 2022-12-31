<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_notifications', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('organization_id')
            ->constrained('organizations')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreignId('notification_id')
            ->constrained('notifications')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->boolean('status')->default(0);

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
        Schema::dropIfExists('organization_notifications');
    }
}
