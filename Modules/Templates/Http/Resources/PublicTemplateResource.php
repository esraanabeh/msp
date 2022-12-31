<?php

namespace Modules\Templates\Http\Resources;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicTemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        // dd($this->user->first_name." ".$this->user->last_name);
        return [
            'type'          => 'Public Template',
            'id'            => $this->id,
            'title'         => $this->title,
            'template_type' => $this->type,
            'last_edited'   => $this->updated_at->format('d/m/Y'),
            'created By'    => $this->user ? $this->user->first_name." ".$this->user->last_name :null,
            'is_admin'      => Auth::user()->role === 'admin',
        ];
    }
}
