<?php

namespace Modules\Client\Listeners;
use Auth;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Client\Events\CreateClientEvent;
use Modules\Client\Notifications\ClientNotification;
use App\Models\Dbnotification;
use Notification;


class ClientListener

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
    public function handle(CreateClientEvent  $event )
    {
        $event->user->notify(new ClientNotification($event->user,$event->client));
    }
}
