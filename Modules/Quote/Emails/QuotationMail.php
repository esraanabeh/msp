<?php

namespace Modules\Quote\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class QuotationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public $client;
    public $link;
    public $quote;

    public function __construct($quote, $client, $code, $data)
    {
        $this->data = $data;
        $this->client = $client;
        $this->link = config('app.front_url').'?code='.$code->code;
        $this->quote = $quote;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address','no-reply@happytexting.com'), 'MSP')
                    ->to($this->client->email,$this->client->contact_person)
                    ->subject('New Quotation')
                    ->view('emails.quote')
                    ->attach($this->data['master_service_agreement']->getFirstMediaUrl('file'),[
                        'as' => 'Master Service Agreement.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->attach($this->quote->getFirstMediaUrl(),[
                        'as' => 'Quotation Details.pdf',
                        'mime' => 'application/pdf',
                    ]);

    }
}
