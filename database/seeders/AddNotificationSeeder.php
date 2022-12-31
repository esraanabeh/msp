<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Notifications\Models\Notification;
use DB;
class AddNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notification = [
            ['title' => 'Client Completed Tasks',
            'notification_key'=>'Client_Task'
           ],

        ];

        Notification::insert($notification);
    }
}
