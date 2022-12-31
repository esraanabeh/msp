<?php

namespace Modules\Members\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Team\Http\Resources\TeamResource;
use Modules\Authentication\Http\Resources\UserResource;
use Modules\Team\Models\Team;
use App\Models\User;
use Modules\Members\Models\Member;

class ListMembersResource extends JsonResource
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
                "id"    => $this->id,
                "member_id"    => $this->member->id,
                'name'  => $this->first_name.' '.$this->last_name,
                'email' => $this->email,
                'role'  => $this->role,
                'status'=> $this->status,
                'team'  => new TeamResource($this->member->team)
            ];


    }
}
