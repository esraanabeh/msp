<?php

namespace Modules\Quote\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Quote\Http\Resources\QuoteSectionResource;
use Modules\Services\Http\Resources\MasterAgreementResource;

class QuoteTemplateResource extends JsonResource
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
            'introduction' => $this->introduction,
            'sections' => QuoteSectionResource::collection($this->sections),
            'master_service_agreement' => $this->when(!is_null($this->master_service_agreement),new MasterAgreementResource($this->master_service_agreement))
        ];
    }
}
