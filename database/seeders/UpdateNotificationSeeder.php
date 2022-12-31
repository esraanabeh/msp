<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Notifications\Models\Notification;
use DB;
class UpdateNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            DB::table('notifications')->where('notification_key', 'Client_code')->update(['notification_key' => 'Client_Quote']);
            DB::table('notifications')->where('title', 'Client Code Acceptance')->update(['title' => 'Client Quote Acceptance']);
    
    }
}
