<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Notifications\Models\OrganizationNotification;

class OrganizationNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organization_notification = [
            ['status' => false,
            'organization_id'=>1,
            'notification_id'=>1,
            ],

            ['status' => false,
            'organization_id'=>1,
            'notification_id'=>2,
            ],

            ['status' => false,
            'organization_id'=>1,
            'notification_id'=>3,
            ],
        ];

        OrganizationNotification::insert($organization_notification);
    }
}
