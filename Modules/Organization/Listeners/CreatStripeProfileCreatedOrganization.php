<?php

namespace Modules\Organization\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\Organization\Models\OrganizationAdmin;
use Modules\Organization\Events\OrganizationCreatedAfter;
class CreatStripeProfileCreatedOrganization
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
        Auth::user()->createOrGetStripeCustomer(
            [
                "name"=>Auth::user()->full_name,
            ]
        );
    }
}
