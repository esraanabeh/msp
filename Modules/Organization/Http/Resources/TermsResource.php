<?php

namespace Modules\Organization\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TermsResource extends JsonResource
{

    public function toArray($request)
    {
        return [

            'terms_and_conditions' => $this->terms_and_conditions,

        ];
    }
}
