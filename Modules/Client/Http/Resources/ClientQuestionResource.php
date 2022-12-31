<?php

namespace Modules\Client\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Organization\Http\Resources\OrganizationResource;
use Modules\Services\Http\Resources\ServiceQuestionResource;
use Modules\Question\Http\Resources\QuestionResource;

class ClientQuestionResource extends JsonResource
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
            'id'         => $this->id,
            'question'   => $this->question,
            //'client'   => new ClientResource($this->client),
            'service'    => new ServiceQuestionResource($this->service),
        ];
    }
}
