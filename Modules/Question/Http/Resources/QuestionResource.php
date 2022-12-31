<?php

namespace Modules\Question\Http\Resources;
use Auth;
use Modules\Services\Models\Service;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Services\Http\Resources\ServiceResource;
use Modules\Client\Http\Resources\ClientResource;

class QuestionResource extends JsonResource
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
            'question' => $this->question,
            'type' => $this->type,
            'service' => new ServiceResource($this->service),
            'client' => new ClientResource($this->client)
        ];
    }
}
