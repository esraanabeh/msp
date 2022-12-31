<?php

namespace Modules\Client\Events;

use Illuminate\Queue\SerializesModels;

class AssignClientTemplate
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $templateId;
    public $clientId;
    public function __construct($templateId, $clientId)
    {
        $this->clientId = $clientId;
        $this->templateId = $templateId;
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
