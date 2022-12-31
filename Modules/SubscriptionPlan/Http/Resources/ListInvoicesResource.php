<?php

namespace Modules\SubscriptionPlan\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ListInvoicesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $dt = Carbon::parse(Carbon::createFromTimestamp($this->created)->toDateTimeString());
        return [
            'id'         => $this->id,
            'created_at' => $dt->shortEnglishMonth ." ".$dt->year,
            'number'     => $this->number,
            'amount'     => $this->amount,
            'status'     => $this->status,
            'pdf_url'    => $this->invoice_pdf,
            'amount_paid' => $this->amount_paid,
        ];
    }
}
