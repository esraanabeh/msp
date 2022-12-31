<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Events\WebhookReceived;
use Modules\Client\Events\CreateClientEvent;
use Modules\Client\Listeners\ClientListener;
use Modules\Client\Events\AcceptQuoteEvent;
use Modules\Client\Events\AssignClientTemplate;
use Modules\Tasks\Listeners\sectionProgressListener;
use Modules\Tasks\Events\sectionProgress;
use Modules\Client\Listeners\AcceptQuoteListner;
use Modules\Client\Listeners\AssignClientTemplateListener;
use Modules\Tasks\Listeners\CompleteTaskListner;
use Modules\Tasks\Events\CompleteTaskEvent;
use Modules\SubscriptionPlan\Listeners\StripeEventListener;
use Modules\Tasks\Events\SendClientEmail;
use Modules\Tasks\Events\SendClientEmailEvent;
use Modules\Tasks\Listeners\SendClientEmailListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        WebhookReceived::class => [
            StripeEventListener::class
        ],

        CreateClientEvent::class=>[
            ClientListener::class
        ],

        sectionProgress::class=>[
            sectionProgressListener::class
        ],

        CompleteTaskEvent::class=>[
            CompleteTaskListner::class
        ],

        AcceptQuoteEvent::class=>[
            AcceptQuoteListner::class
        ],
        SendClientEmailEvent::class=>[
            SendClientEmailListener::class
        ],
        AssignClientTemplate::class => [
            AssignClientTemplateListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
