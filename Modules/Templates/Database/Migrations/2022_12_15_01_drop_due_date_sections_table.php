<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\Templates\Models\Section;

class DropDueDateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
       if (Schema::hasColumn('sections', 'due_date'))
        {
            Schema::table('sections', function (Blueprint $table) {
                $table->dropColumn('due_date');
            });
        }
        if (Schema::hasColumn('sections', 'is_completed'))
        {
            Schema::table('sections', function (Blueprint $table) {
                $table->dropColumn('is_completed');
            });
        }
        if (Schema::hasColumn('sections', 'progress'))
        {
            Schema::table('sections', function (Blueprint $table) {
                $table->dropColumn('progress');
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
        Schema::table('sections', function (Blueprint $table) {
            $table->date('due_date');
            $table->boolean('is_completed');
            $table->float('progress');
        });
    }
}
