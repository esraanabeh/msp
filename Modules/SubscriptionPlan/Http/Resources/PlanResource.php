<?php

namespace Modules\SubscriptionPlan\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'name' => $this->name,
            'currency' => $this->currency,
            'yearly_price' => $this->yearly_price,
            'features'=> PlanFeatureResource::collection($this->Features)->response()->getData(true),
        ];
    }
}
