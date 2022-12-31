<?php

namespace Modules\Tasks\Http\Resources;
use Modules\Templates\Http\Resources\SectionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'title' => $this->title,
            'type' => $this->type,
            'description' => $this->description,
            'is_completed' => $this->done,
            'order' => $this->display_order,
            'options' =>$this->options ?? json_decode($this->options),
            'section'=> new  SectionResource($this->section),
            'file' => strlen( $this->getFirstMediaUrl('file'))> 0 ? $this->getFirstMediaUrl('file') :null
        ];
    }
}
