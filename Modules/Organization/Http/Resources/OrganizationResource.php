<?php

namespace Modules\Organization\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'                   => $this->id,
            'name'                 => $this->name,
            'team_members'         => $this->team_members,
            'main_speciality'      => $this->main_speciality,
            'phone_number'         => $this->phone_number,
            'website_url'          => $this->website_url,
            'terms_and_conditions' => $this->terms_and_conditions,
            'email '               => $this->email,
            'logo '                => $this->getFirstMediaUrl('logo'),
            'status '              => $this->status,
            'created_at'           => $this->created_at,

        ];
    }
}
