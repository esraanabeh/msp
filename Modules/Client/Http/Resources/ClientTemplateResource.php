<?php

namespace Modules\Client\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Organization\Http\Resources\OrganizationResource;
use Modules\Templates\Http\Models\TemplateClient;
use Modules\Templates\Http\Resources\TemplateResource;

class ClientTemplateResource extends JsonResource
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
            'id'        => $this->id,
            'title'     => $this->title,
            'sections'  => ClientSectionResource::collection($this->sections()->withTrashed()->get()),
        ];
    }
}
