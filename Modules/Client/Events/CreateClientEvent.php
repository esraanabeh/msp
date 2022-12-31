<?php

namespace Modules\Client\Events;

use Illuminate\Queue\SerializesModels;

class CreateClientEvent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $client;
    public $user;
    public function __construct($client , $user)
    {

        $this->client=$client;
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
