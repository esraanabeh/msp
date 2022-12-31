<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToCustomNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_notifications', function (Blueprint $table) {
            $table->string('notification_key')->nullable();
            $table->foreign('notification_key')
            ->references('notification_key')
            ->on('notifications')
            ->onDelete('cascade')
            ->onUpdate('cascade')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_notifications', function (Blueprint $table) {
            $table->dropColumn('notification_key');
        });
    }
}
