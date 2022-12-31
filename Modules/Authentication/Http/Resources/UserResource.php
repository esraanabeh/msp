<?php

namespace Modules\Authentication\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Organization\Http\Resources\OrganizationResource;
use Modules\Team\Http\Resources\TeamResource;

class UserResource extends JsonResource
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
            'first_name'   => $this->first_name,
            'last_name'    => $this->last_name,
            'email'        => $this->email,
            'avatar'       => $this->getFirstMediaUrl('avatar') ,
            'is_verified'  => $this->is_verified,
            'status'       => $this->status,
            'allow_2fa'    => $this->allow_2fa,
            'role'         => $this->role ,
            'phone_number' => $this->phone_number,
            'organization' => new OrganizationResource($this->getOrganization()),
            'team'         => $this->member()->exists() ? new TeamResource($this->member->team) : "Owner",
            'is_admin'     => $this->organization_admin ? true : false
        ];
    }
}
