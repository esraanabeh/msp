<?php

namespace Modules\Tasks\Events;

use Illuminate\Queue\SerializesModels;

class CompleteTaskEvent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $task;
    public $user;
    public $taskId;
    public function __construct($task , $user ,$taskId)
    {


        $this->task=$task;
        $this->user=$user;
        $this->taskId=$taskId;
       

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
