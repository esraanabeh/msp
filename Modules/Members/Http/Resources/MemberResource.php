<?php

namespace Modules\Members\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Team\Http\Resources\TeamResource;
use Modules\Authentication\Http\Resources\UserResource;
use Modules\Team\Models\Team;
use App\Models\User;
use Modules\Members\Models\Member;

class MemberResource extends JsonResource
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
                'name'  => $this->user->first_name.' '.$this->user->last_name,
                'email'  => $this->user->email,
                'role'=>$this->user->role,
                'status'=>$this->user->status,
                'is_verified'=>$this->user->is_verified,
                'team' =>$this->team->name
            ];


    }
}
