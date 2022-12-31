<?php

namespace Modules\SubscriptionPlan\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SubscriptionPlanFeaturesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $features = [
            ['details' => "Pause and resume subscriptions ",
            'plan_id' => '1',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString()
            ],
            ['details' => "Lost opportunities reports",
            'plan_id' => '1',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString()
            ],
            ['details' => "Inventory Integration",
            'plan_id' => '1',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString()
            ],
            ['details' => "Consolidated customer interactions",
            'plan_id' => '1',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString()
            ],
            ['details' => "Document attachments",
            'plan_id' => '1',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString()
            ],
            ['details' => "Prefilled checkout page",
            'plan_id' => '1',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString()
            ],
            ['details' => "Cancellation reports",
            'plan_id' => '1',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString()
            ],
            ['details' => "Invoice reminders",
            'plan_id' => '1',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString()
            ],
        ];

        DB::table('plans_features')->insert($features);
        
    }
}
