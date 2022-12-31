<?php

namespace Modules\Client\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Organization\Http\Resources\OrganizationResource;
use Modules\Client\Http\Resources\ClientServiceResource;

class ClientResource extends JsonResource
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
            'id'                  => $this->id,
            'contact_person'      => $this->contact_person,
            'phone_number'        => $this->phone_number,
            'email'               => $this->email,
            'address'             => $this->address,
            'industry'            => $this->industry,
            'contact_person'      => $this->contact_person,
            'company_name'        => $this->company_name,
            'status'              => $this->status,
            'number_of_employees' => $this->number_of_employees,
            'additional_questions'=> $this->additional_questions,
            'created_at'          => $this->created_at,
            'MMR'                 => 'STATIC DATA',
            'true_profit'         => 'STATIC DATA',
            'organization'        => new OrganizationResource($this->organization),

        ];
    }
}
