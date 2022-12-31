<?php

namespace Modules\Templates\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Team\Http\Resources\TeamResource;

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
            'id' => $this->id,
            'name' => $this->name,
            'progress' => isset($this->client_sections[0]) ? $this->client_sections[0]->progress : null,
           'due_date' => isset($this->client_sections[0]) ? $this->client_sections[0]->due_date : null,
            'start_date' => $this->created_at,
            'shared_with_client' => $this->shared_with_client,
            'team' => new TeamResource($this->team)
        ];
    }
}
