<?php

namespace Modules\Services\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MasterAgreementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $file=$this->media()->first();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'isDefault' => $this->isDefault,
            'file' => $this->getFirstMediaUrl('file'),
            'extension'=>$file ? $file->mime_type : null,



        ];
    }
}
