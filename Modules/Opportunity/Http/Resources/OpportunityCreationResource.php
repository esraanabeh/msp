<?php

namespace Modules\Opportunity\Http\Resources;
use Modules\Client\Models\ClientQuestion;
use Modules\Question\Models\Question;
use Illuminate\Http\Resources\Json\JsonResource;

class OpportunityCreationResource extends JsonResource
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
            'id'      => $this->id,
            'service' => $this->service->title,
            'notes'   => $this->opportunity_notes,
            'client'  => $this->client
        ];
    }
}
