<?php

namespace Modules\Dashboard\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Team\Http\Resources\TeamResource;
use Modules\Templates\Http\Resources\SectionResource;

class ClientSectionResource extends JsonResource
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
            'id' => $this->section->id,
            'name' => $this->section->name,
            'due_date' => $this->due_date,
            'start_date' => $this->created_at,
            'progress' => $this->progress,
            'template' => new TemplateClientResource($this->section->template)
        ];
    }
}
