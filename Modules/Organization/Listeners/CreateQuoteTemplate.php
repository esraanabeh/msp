<?php

namespace Modules\Organization\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Organization\Events\OrganizationCreatedAfter;
use Modules\Quote\Models\QuoteTemplate;

class CreateQuoteTemplate
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrganizationCreatedAfter $event)
    {
        $template = new QuoteTemplate();

        $template->introduction = config('app.default_introduction');
        $template->organization_id =$event->organization->id;
        $template->save();
    }
}
