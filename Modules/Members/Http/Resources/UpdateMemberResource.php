<?php

namespace Modules\Members\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Team\Http\Resources\TeamResource;
use Modules\Authentication\Http\Resources\UserResource;
use Modules\Team\Models\Team;
use Modules\Member\Models\Member;
use App\Models\User;
class UpdateMemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {


        // dd('sss');
        return [



            'user'=>  new UserResource ($this->resource),
            'team' =>$this->member->team->name



        ];




    }
}
