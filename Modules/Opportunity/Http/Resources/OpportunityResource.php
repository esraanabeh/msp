<?php

namespace Modules\Opportunity\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Client\Http\Resources\ClientQuestionResource;

class OpportunityResource extends JsonResource
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
            'id'             => $this->id,
            'contact_person' => $this->contact_person,
            'company_name'   => $this->company_name,
            'status'         => $this->status,
            'opportunities_count'  => $this->opportunities_count,
            //'opportunities_questions'  => ClientQuestionResource::collection($this->questions)
        ];
    }
}
