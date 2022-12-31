<?php

namespace Modules\FAQ\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FAQWithCatResource extends JsonResource
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

            'id' => $this->resource[0]->category->id ?? null,
            'name' => $this->resource[0]->category->name ?? null,
            'FAQ' =>   FAQResource::collection($this->resource),

        ];
    }
}
