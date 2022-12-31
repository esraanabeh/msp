<?php

namespace Modules\Industries\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Organization\Http\Resources\OrganizationResource;

class IndustryResource extends JsonResource
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
            'title' => $this->title,
            'organization_id'=>$this->organization_id,

        ];
    }
}
