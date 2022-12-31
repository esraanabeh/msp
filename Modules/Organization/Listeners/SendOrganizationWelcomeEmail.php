<?php

namespace Modules\Organization\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Organization\Events\OrganizationCreatedAfter;
use Modules\Organization\Notifications\OrganizationWelcomeEmail;

class SendOrganizationWelcomeEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrganizationCreatedAfter  $event)
    {
        $event->user->notify(new OrganizationWelcomeEmail($event->user));
    }
}
