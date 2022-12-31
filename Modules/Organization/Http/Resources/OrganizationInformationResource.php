<?php

namespace Modules\Organization\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationInformationResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email ' => $this->email,
            'phone_number' => $this->phone_number,
            'website_url' => $this->website_url,
        ];
    }
}
