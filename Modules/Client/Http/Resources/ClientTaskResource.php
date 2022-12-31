<?php

namespace Modules\Client\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Organization\Http\Resources\OrganizationResource;
use Modules\Services\Http\Resources\ServiceResource;
use Modules\Question\Http\Resources\QuestionResource;

class ClientTaskResource extends JsonResource
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
           'id'            => $this->id,
           'title'         => $this->task->title,
           'options'       => $this->task->options,
           'type'          => $this->task->type,
           'description'   => $this->task->description,
           'display_order' => $this->task->display_order,
           'status'        => $this->status,
           'is_compeleted' => $this->done,
           'answer'        => $this->answer,
           'file'          => $this->task->getFirstMediaUrl('file'),
           'answered_file' => $this->getFirstMediaUrl('file')
        ];
    }
}
