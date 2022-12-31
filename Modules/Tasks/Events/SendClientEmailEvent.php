<?php

namespace Modules\Tasks\Events;

use Illuminate\Queue\SerializesModels;

class SendClientEmailEvent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    // public $user;
    public function __construct($request)
    {
        $this->request=$request;
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
