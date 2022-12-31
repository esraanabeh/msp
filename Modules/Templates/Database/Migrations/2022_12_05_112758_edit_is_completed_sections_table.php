<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\Templates\Models\Section;

class EditIsCompletedSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        if (Schema::hasColumn('sections', 'is_completed'))
        {
            Schema::table('sections', function (Blueprint $table) {
                $table->boolean('is_completed')->default(0)->change();
            });
            Section::whereNull("is_completed")->update(['is_completed' => 0]);

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
            $table->dropColumn('is_completed');

        });
    }
}
