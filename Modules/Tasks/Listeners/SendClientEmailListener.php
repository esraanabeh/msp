<?php

namespace Modules\Tasks\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\Client\Models\Client;
use Modules\Tasks\Events\SendClientEmail;
use Modules\Tasks\Events\SendClientEmailEvent;
use Modules\Tasks\Notifications\SendClientMail;

class SendClientEmailListener
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
    public function handle(SendClientEmailEvent $event)
    {
        $client = Client::where("email",$event->request->email)
                ->first();

        $client->notify(new SendClientMail($event->request->title,$event->request->content));
    }
}
