<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\SubscriptionPlan\Database\Seeders\SubscriptionPlanDatabaseSeeder;
use Modules\SubscriptionPlan\Database\Seeders\SubscriptionPlanFeaturesDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(NotificationSeeder::class);
        // $this->call(OrganizationNotificationSeeder::class);
        // $this->call(SubscriptionPlanDatabaseSeeder::class);
        // $this->call(SubscriptionPlanFeaturesDatabaseSeeder::class);
        // $this->call(CategorySeeder::class);
        // $this->call(FAQSeeder::class);
        // $this->call(UpdateNotificationSeeder::class);
        // $this->call(AddNotificationSeeder::class);
    }
}
