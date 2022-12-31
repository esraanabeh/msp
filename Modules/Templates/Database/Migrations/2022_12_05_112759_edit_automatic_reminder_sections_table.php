<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\Templates\Models\Section;

class EditAutomaticReminderSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Section::whereNull("automatic_reminder")->withTrashed()->update(['automatic_reminder' => 0]);
        Section::whereNull("is_completed")->withTrashed()->update(['is_completed' => 0]);
        if (Schema::hasColumn('sections', 'automatic_reminder'))
        {
            Schema::table('sections', function (Blueprint $table) {
                $table->boolean('automatic_reminder')->default(0)->nullable(false)->change();
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
            $table->dropColumn('automatic_reminder');

        });
    }
}
