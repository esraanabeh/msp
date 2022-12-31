<?php

namespace Modules\Organization\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\Organization\Models\OrganizationAdmin;
use Modules\Organization\Events\OrganizationCreatedAfter;
class AssignAdminToCreatedOrganization
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
        $organizationAdmin = new OrganizationAdmin();
        $organizationAdmin->user_id=$event->user->id;
        $organizationAdmin->organization_id =$event->organization->id;
        $organizationAdmin->save();

        $user = $event->user;
        $user->organization_id = $event->organization->id;
        $user->save();
    }
}
