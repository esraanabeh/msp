<?php

namespace Modules\Organization\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\Notifications\Models\Notification;
use Modules\Notifications\Models\OrganizationNotification;
use Modules\Organization\Models\OrganizationAdmin;
use Modules\Organization\Events\OrganizationCreatedAfter;
class CreateNotificationSettingsCreatedOrganization
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrganizationCreatedAfter $event)
    {
        $notifications=Notification::get('id')->pluck('id');
        $array=[];
        foreach ($notifications as $notification){
            $data=['organization_id' => $event->organization->id,
            'notification_id' => $notification,
            'status' =>false];
            $array[]=$data;
        };
        OrganizationNotification::insert( $array );
     
    }
}
