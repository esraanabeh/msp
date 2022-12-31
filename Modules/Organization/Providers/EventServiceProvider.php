<?php

namespace Modules\Organization\Providers;
use Modules\Organization\Events\OrganizationCreatedAfter;
use Modules\Organization\Listeners\AssignAdminToCreatedOrganization;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Organization\Listeners\CreateNotificationSettingsCreatedOrganization;
use Modules\Organization\Listeners\CreatStripeProfileCreatedOrganization;
use Modules\Organization\Listeners\CreateQuoteTemplate;
use Modules\Organization\Listeners\SendOrganizationWelcomeEmail;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrganizationCreatedAfter::class => [
            AssignAdminToCreatedOrganization::class,
            CreateNotificationSettingsCreatedOrganization::class,
            CreatStripeProfileCreatedOrganization::class,
            CreateQuoteTemplate::class,
            SendOrganizationWelcomeEmail::class
        ]
    ];


    public function boot()
    {
        //
    }
}
