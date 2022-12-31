<?php

namespace Modules\Dashboard\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Team\Http\Resources\TeamResource;
use Modules\Templates\Http\Resources\SectionResource;

class TemplateClientResource extends JsonResource
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
            'template'=>$this ? $this->title :null,
            'sections' =>$this ? SectionResource::collection($this->sections) :null
        ];
    }
}
