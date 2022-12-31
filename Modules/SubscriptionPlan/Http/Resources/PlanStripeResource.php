<?php

namespace Modules\SubscriptionPlan\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanStripeResource extends JsonResource
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
            'id'                   => $this->id,
            'name'                 => $this->name,
            'status'               => $this->stripe_status,
            'trial_ends_at'        => $this->trial_ends_at,
            'ends_at'              => $this->ends_at,
            'current_period_start' => $this->current_period_start,
            'current_period_end'   => $this->current_period_end,
            'created_at'           => $this->created_at,
            'left_days'            => is_null($this->trial_ends_at) ? NULL: Carbon::parse($this->created_at)->diffInDays(Carbon::now()),
            'plan'                 => new PlanResource($this->plan),
        ];
    }
}
