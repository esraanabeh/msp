<?php

namespace Modules\Dashboard\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Dashboard\Http\Resources\TemplateClientResource;

class DashboardDetailsResource extends JsonResource
{

    // public static $wrap = null;


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'Client' => $this->contact_person,
            'templates'=>   TemplateClientResource::collection($this->templates)
        ];
    }

}
