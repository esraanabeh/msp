<?php

namespace Modules\SubscriptionPlan\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PreviewInvoiceResource extends JsonResource
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
            'amount'     => is_null($this->amount_paid) ? NULL : $this->amount_paid/100,
            'status'     => $this->paid,
            'pdf_url'    => $this->invoice_pdf,
            'payment_details' => $this->payment_details,
        ];
    }
}
