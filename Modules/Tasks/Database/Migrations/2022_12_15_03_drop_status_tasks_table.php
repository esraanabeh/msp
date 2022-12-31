<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\Templates\Models\Section;

class DropStatusTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
       if (Schema::hasColumn('tasks', 'status'))
        {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
        if (Schema::hasColumn('tasks', 'answer'))
        {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('answer');
            });
        }
        if (Schema::hasColumn('tasks', 'done'))
        {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('done');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('status',['open',
                            'pending','in_progress',
                            'done']);

            $table->string('answer')->nullable();
            $table->boolean('done')->default(false);
        });
    }
}
