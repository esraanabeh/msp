<?php

namespace Modules\Tasks\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Team\Http\Resources\TeamResource;
use Carbon\Carbon;
class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'team' => new TeamResource($this->team),
            'description' => $this->description,
            'automatic_reminder' => $this->automatic_reminder,
            'reminder_day' => $this->reminder_day,
            // 'progress' => $this->progress,
            // 'due_date' =>Carbon::parse( $this->due_date) ,
            'reminder_time' => $this->reminder_time,
            'shared_with_client' => $this->shared_with_client,
            'email_template' => $this->email_template,
            'tasks' => TaskResource::collection($this->tasks)
        ];
    }
}
