<?php

namespace Modules\Opportunity\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Services\Models\Service;
class OpportunityPreviewResource extends JsonResource
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
            'notes'   => $this->notes,
            'service' => $this->service,
            'client_id' => $this->client->id,
            'client_name' => $this->client->contact_person,
            'client_email' => $this->client->email
        ];
    }
}
