<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Notifications\Models\Notification;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notification = [
            ['title' => 'New MRR Calculated',
             'notification_key'=>'MMR'
            ],
            ['title' => 'Client Quote Acceptance',
              'notification_key'=>'Client_Quote_Acceptance'
            ],
            ['title' => 'Staff Completed Tasks',
             'notification_key'=>'Member_Complete_Task'
            ],
        ];

        Notification::insert($notification);
    }
}
