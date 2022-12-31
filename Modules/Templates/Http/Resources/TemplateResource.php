<?php

namespace Modules\Templates\Http\Resources;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResource extends JsonResource
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
            'id'              => $this->id,
            'title'           => $this->title,
            'description'     => $this->description,
            'template_type'   => $this->type,
            'public_template' => is_null($this->owner_id),
            'section'         => SectionResource::collection($this->sections),

        ];
    }
}
