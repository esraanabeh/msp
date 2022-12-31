<?php

namespace Modules\Client\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Organization\Http\Resources\OrganizationResource;
use Modules\Services\Http\Resources\ServiceResource;
use Modules\Question\Http\Resources\QuestionResource;

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
           'id'       => $this->id,
           'name'     => $this->name,
           'team'     => $this->team->name,
           'progress' => isset($this->client_sections[0]) ? $this->client_sections[0]->progress : null,
           'due_date' => isset($this->client_sections[0]) ? $this->client_sections[0]->due_date : null
        ];
    }
}
