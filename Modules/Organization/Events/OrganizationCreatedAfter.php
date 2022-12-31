<?php

namespace Modules\Organization\Events;

use Illuminate\Queue\SerializesModels;

class OrganizationCreatedAfter
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $organization;
    public $user;
    public function __construct($organization , $user)
    {
        $this->organization=$organization;
        $this->user=$user;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
