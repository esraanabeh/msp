<?php

namespace Modules\SubscriptionPlan\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SubscriptionPlanDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::table('plans')->insert([
            'name' => "Becaome a Pro",
            'currency' => 'USD',
            'yearly_price' => '499',
            'provider_id' => 'price_1LzKDlJ9zjN0JOTqQJAo00rR',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);
    }
}
