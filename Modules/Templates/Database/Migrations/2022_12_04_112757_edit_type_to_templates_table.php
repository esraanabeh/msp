<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class EditTypeToTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('templates', 'type'))
        {
            Schema::table('templates', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
        
        Schema::table('templates', function (Blueprint $table) {
            $table->enum('type',['general','custom'])->default('general');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn('type');

        });
    }
}
