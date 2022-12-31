<?php

namespace Modules\Client\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Organization\Http\Resources\OrganizationResource;
use Modules\Client\Http\Resources\ClientResource;
use Modules\Services\Http\Resources\ServiceResource;

class ClientMRRServiceResource extends JsonResource
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
            'title'         => $this->title,
            'unit_cost'     => $this->unit_cost,
            'type'          => $this->type,
            'opportunities' => $this->opportunities ? $this->opportunities->where("client_id",$request->clientId) : null,
            "MRR_services"  => $this->ClientMRRServices ? $this->ClientMRRServices->where("pivot.client_id",$request->clientId)->pluck('pivot')->unique()->all() : null,

        ];
    }
}
