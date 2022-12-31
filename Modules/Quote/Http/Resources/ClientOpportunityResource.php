<?php

namespace Modules\Quote\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientOpportunityResource extends JsonResource
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
            'notes' => $this->notes,
            'qty' => $this->qty,
            'total_amount' => $this->total_amount,
            'service' => [
                'id' => $this->service->id,
                'title' => $this->service->title,
                'unit_cost' => $this->service->unit_cost
            ]
        ];
    }
}
