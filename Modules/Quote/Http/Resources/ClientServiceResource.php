<?php

namespace Modules\Quote\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientServiceResource extends JsonResource
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
            'cost' => $this->pivot->cost,
            'qty'  =>$this->pivot->qty,
            'type' =>$this->type,
            'total_amount' =>$this->pivot->total_amount
        ];
    }
}
