<?php

namespace Modules\Notifications\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use  Modules\Authentication\Http\Resources\UserResource;
use app\Models\User;
class NotificationResource extends JsonResource
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
            'title' => $this->notification->title,
            'status' => $this->status,
        ];
    }
}
