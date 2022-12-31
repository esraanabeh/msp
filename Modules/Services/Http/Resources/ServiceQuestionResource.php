<?php

namespace Modules\Services\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'unit_cost' => $this->unit_cost,
            'type' => $this->type,

        ];
    }
}
