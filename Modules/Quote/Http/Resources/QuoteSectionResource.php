<?php

namespace Modules\Quote\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuoteSectionResource extends JsonResource
{
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
            'title' => $this->title,
            'content' => $this->content,
            'file' => $this->getFirstMediaUrl()
        ];
    }
}
