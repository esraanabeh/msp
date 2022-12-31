<?php

namespace Modules\Tasks\Events;

use Illuminate\Queue\SerializesModels;

class sectionProgress
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $task;
    // public $user;
    public function __construct($task )
    {
        $this->task=$task;
     
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
