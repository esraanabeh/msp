<?php

namespace Modules\SubscriptionPlan\Listeners;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookReceived;
use Modules\SubscriptionPlan\Models\Subscription;
use Modules\SubscriptionPlan\Notifications\TrialEndReminder;

class StripeEventListener
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
    public function handle(WebhookReceived $event)
    {
        switch ($event->payload['type']) {
            case 'customer.created':
                Log::info('new stripe customer created');
                break;

            case 'charge.succeeded':
                Log::info('new stripe charge created');
                break;

            case 'customer.subscription.created':
                Log::info('new stripe customer subscription created');
                Log::info($event->payload['data']['object']);
                $this->updateSubscriptionDetails($event->payload['data']['object']);
                break;
            case 'customer.subscription.updated':
                Log::info('new stripe customer subscription updated');
                Log::info($event->payload['data']['object']);
                $this->updateSubscriptionDetails($event->payload['data']['object']);
                break;
            case 'customer.subscription.trial_will_end':
                Log::info("event trial will end recieved");
                $this->trialEndReminder($event->payload['data']['object']);
            default:
                Log::info($event->payload['type']);
                break;
        }
    }


    public function updateSubscriptionDetails($payload)
    {
        $Subscription = Subscription::where("stripe_id",$payload['id'])
                        ->first();
        if(!is_null($Subscription)){
            $Subscription->current_period_start = Carbon::createFromTimestampUTC($payload['current_period_start']);
            $Subscription->current_period_end   = Carbon::createFromTimestampUTC($payload['current_period_end']);
            $Subscription->stripe_status        = $payload['status'];
            $Subscription->save();
        }
    }

    public function trialEndReminder($payload)
    {
        $endDate = new Carbon($payload['current_period_end']);
        $data = [
            'start_date' => new Carbon($payload['current_period_start']),
            'end_date' => $endDate->format('d M Y')
        ];

        $user = User::where('stripe_id',$payload['customer'])->first();

        $user->notify(new TrialEndReminder($data));
    }
}
