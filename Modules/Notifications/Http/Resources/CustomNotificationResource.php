<?php

namespace Modules\Notifications\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use  Modules\Authentication\Http\Resources\UserResource;

class CustomNotificationResource extends JsonResource
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
            'data' => $this->data,
            'read_at' => $this->read_at,

        ];
    }
}
