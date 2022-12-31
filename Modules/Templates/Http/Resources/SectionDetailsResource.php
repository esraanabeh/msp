<?php

namespace Modules\Templates\Http\Resources;
use Modules\Templates\Models\section;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Team\Http\Resources\TeamResource;
use Modules\Tasks\Http\Resources\TaskResource;
use Modules\Team\Models\Team;

class SectionDetailsResource extends JsonResource
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
            'id'                  => $this->id,
            'section'             => $this->name,
            'section_description' => $this->description,
            'due_date'            => $this->due_date,
            'reminder_day'        => $this->reminder_day,
            'reminder_time'       => $this->reminder_time,
            'automatic_reminder'  => $this->automatic_reminder,
            'is_completed'        => $this->is_completed,
            'shared_with_client'  => $this->shared_with_client,
            'client'              => $this->template->template_client,
            'assigned_team'       => new TeamResource($this->team),
            'teams'               => TeamResource::collection(Team::where("organization_id",Auth::user()->organization_id)->where("status",1)->get()),
            'tasks'               => TaskResource::collection($this->tasks->sortBy('display_order'))
        ];
    }
}
