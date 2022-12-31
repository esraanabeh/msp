<?php

namespace Modules\Organization\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'logo ' => $this->getFirstMediaUrl('logo'),
            'plan' => $this->plan ? $this->plan->stripe_status : null
        ];
    }
}
