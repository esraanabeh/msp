<?php

namespace Modules\SubscriptionPlan\Repositories;

use App\Http\Helpers\Helper;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Modules\Authentication\Http\Resources\UserResource;
use Modules\SubscriptionPlan\Http\Resources\PlanResource;
use Modules\SubscriptionPlan\Models\Plan;
use Modules\SubscriptionPlan\Repositories\Interfaces\ISubscriptionPlanRepository;
use Modules\SubscriptionPlan\Traits\ErrorHandler;
use Illuminate\Support\Facades\Auth;
use Modules\SubscriptionPlan\Http\Resources\PlanStripeResource;
use Modules\SubscriptionPlan\Models\Subscription;

class SubscriptionPlanRepository  implements ISubscriptionPlanRepository{
    use ErrorHandler;

    // List all MSP plans
    public function listPlans()
    {
        $plans = Plan::where("status",true)->get();
        return [
            'data' => PlanResource::collection($plans)->response()->getData(true),
            'status' => true,
            'identifier_code' => 160001,
            'status_code' => 200,
            'message' => 'List of plans'
        ];

    }

    // Get current organization plan
    public function getMyPlan()
    {
        $subscription = Subscription::where("user_id",Auth::user()->id)
                                    ->where(function ($q){
                                        $q->Where("stripe_status","trialing")
                                        ->orWhere("stripe_status","active");
                                    })
                                    ->WhereNull("ends_at")
                                    ->orderBy("id","DESC")
                                    ->with("plan")
                                    ->first();
        if(!is_null($subscription))
        {
            return [
                'data' => new PlanStripeResource($subscription),
                'status' => true,
                'identifier_code' => 179001,
                'status_code' => 200,
                'message' => 'my subscribed plan'
            ];
        }
        else
        {
            return [
                'data' => NULL,
                'status' => false,
                'identifier_code' => 179002,
                'status_code' => 400,
                'message' => "You don't have any active subscription plan"
            ];
        }
        

    }

    // subscribe yearly
    public function subscribeYearly($data)
    {
        $subscription = Subscription::where("user_id",Auth::user()->id)
                                    ->orderBy("id","DESC")
                                    ->with("plan")
                                    ->first();
        if(is_null($subscription) || (!is_null($subscription) && !in_array($subscription->stripe_status ,["trialing","active"])) || (!is_null($subscription) && !is_null($subscription->ends_at) ))
        {
            try
            {
                    // step:1 => create payment method
                    $stripe = new \Stripe\StripeClient(
                        config('cashier.secret')
                    );
                    $source = $stripe->customers->createSource(
                        Auth::user()->stripe_id,
                        ['source' => $data['token']]
                                );
    
                    // step: 2 => charge the customer
    
                    $plan = Plan::first();
                    $user = Auth::user();
                    $result =  $user->newSubscription('default', $plan->provider_id)
                                ->createAndSendInvoice([
                                    'payment_behavior'=>'error_if_incomplete',
                                    'proration_behavior'=>'none'],
                                    ['days_until_due' => 0,
                                    'payment_behavior'=>'error_if_incomplete',
                                    'proration_behavior'=>'none']);
    
                    return [
                        'data' => new UserResource(Auth::user()),
                        'status' => true,
                        'identifier_code' => 178001,
                        'status_code' => 200,
                        'message' => 'subscription proccess completed successfully'
                    ];
    
            }
            catch (\Exception $e)
            {
                Log::error("trial subscription failed :",[$e->getMessage()]);
                return [
                    'data' => new UserResource(Auth::user()),
                    'status' => false,
                    'identifier_code' => 178002,
                    'status_code' => 400,
                    'message' => $this->stripeErrorHandler($e->getJsonBody())
                ];
            }
        }
        else
        {
            return [
                'data' => new UserResource(Auth::user()),
                'status' => false,
                'identifier_code' => 178003,
                'status_code' => 400,
                'message' => "You already have an active plan "
            ];  
        }
        
    }

    // subscribe trial
    public function subscripeTrial()
    {
        $activeSubscription = Subscription::where("user_id",Auth::user()->id)
                                ->orderBy("id","DESC")
                                ->with("plan")
                                ->first();
        if(!$activeSubscription)
        {
            try
            {
                $plan = Plan::first();
                $user = Auth::user();
        
                $user->createOrGetStripeCustomer();
                $result =  $user->newSubscription('default',  $plan->provider_id)
                            ->trialDays(30)
                            ->create();
        
                return [
                    'data' =>new UserResource(Auth::user()) ,
                    'status' => true,
                    'identifier_code' => 177001,
                    'status_code' => 200,
                    'message' => 'You Have 30 days as a trial period for our system'
                ];
    
            }
            catch (\Exception $e)
            {
                Log::error("trial subscription failed :",[$e->getMessage()]);
                return [
                    'data' => new UserResource(Auth::user()),
                    'status' => false,
                    'identifier_code' => 177002,
                    'status_code' => 400,
                    'message' => $this->stripeErrorHandler($e->getJsonBody())
                ];
            }
        }
        else
        {
            return [
                'data' => new UserResource(Auth::user()),
                'status' => false,
                'identifier_code' => 177003,
                'status_code' => 400,
                'message' => "You are already subscribed before, You cann't subscribe in the trial plan"
            ];
        }                     
        
    }

    // Add new payment method
    public function createPaymentMethod($data)
    {
        $checkExistance = $this->checkCardExistBefore(Auth::user(),$data['token']);
        if($checkExistance)
        {
            return [
                'data' => NULL,
                'status' => false,
                'identifier_code' => 180002,
                'status_code' => 400,
                'message' => 'Credit card already exist'
            ];
        }
        else
        {
            try
            {
                    // step:1 => create payment method
                    $stripe = new \Stripe\StripeClient(
                        config('cashier.secret')
                    );
                    $source = $stripe->customers->createSource(
                        Auth::user()->stripe_id,
                        ['source' => $data['token']]);

                    request()->is_default || !Auth::user()->hasDefaultPaymentMethod() ? Auth::user()->updateDefaultPaymentMethod($source->id) : NULL;

                    return [
                        'data' => $source,
                        'status' => true,
                        'identifier_code' => 180001,
                        'status_code' => 200,
                        'message' => 'Credit card details added to you account successfully'
                    ];

            }
            catch (\Exception $ex)
            {
                Log::error("add new payment method failed :",[$ex->getMessage()]);
                return [
                    'data' => $ex->getMessage(),
                    'status' => false,
                    'identifier_code' => 180003,
                    'status_code' => 400,
                    'message' => $this->stripeErrorHandler($ex->getJsonBody())
                ];
            }
        }

    }


    // Update payment method
    public function updatePaymentMethod($data){
        // configure strip
        $stripe = new \Stripe\StripeClient(
            config('cashier.secret')
        );


        try
        {
            // update customer card data
            $source = $stripe->customers->updateSource(
                Auth::user()->stripe_id,
                $data['card_id'],
                [
                    'name'        => $data['name'],
                    'address_zip' => $data['address_zip'],
                    'exp_month'   => explode('/', $data['expiration_date'])[0],
                    'exp_year'    => explode('/', $data['expiration_date'])[1],
                ]);
                request()->is_default ? Auth::user()->updateDefaultPaymentMethod($source->id) : NULL;
                return [
                    'data' => $source,
                    'status' => true,
                    'identifier_code' => 181001,
                    'status_code' => 200,
                    'message' => 'Credit card details updated successfully'
                ];

        }
        catch(\Exception $ex)
        {
            Log::error("update payment method failed due to:",[$ex->getMessage()]);
            return [
                'data' => $ex->getMessage(),
                'status' => false,
                'identifier_code' => 181002,
                'status_code' => 400,
                'message' => $this->stripeErrorHandler($ex->getJsonBody())
            ];
        }
    }


    // List all payment methods
    public function listPaymentMethods(){
        $stripe = new \Stripe\StripeClient(
            config('cashier.secret')
        );
        $cards   = $stripe->paymentMethods->all(['customer' => Auth::user()->stripe_id , 'type' => 'card']);
    
        return [
            'data'            => Helper::paginate($cards['data'],config('app.per_page')),
            'status'          => true,
            'identifier_code' => 185001,
            'status_code'     => 200,
            'message'         => 'List of cards'
        ];
    }


    // Delete Payment Method
    public function deletePaymentMethod($data){
        // configure strip
        $stripe = new \Stripe\StripeClient(
            config('cashier.secret')
        );

        $cards   = $stripe->paymentMethods->all(['customer' => Auth::user()->stripe_id , 'type' => 'card']);
        if(count ($cards['data']) > 1)
        {
            try
            {
                $source = $stripe->customers->deleteSource(
                    Auth::user()->stripe_id,
                    $data['card_id'],
                    []
                  );
                return [
                    'data'            => $source,
                    'status'          => true,
                    'identifier_code' => 186001,
                    'status_code'     => 200,
                    'message'         => 'Credit card deleted successfully'
                ];
            }
            catch(\Exception $ex)
            {
                Log::error("delete payment method failed due to:",[$ex->getMessage()]);
                return [
                    'data'            => $ex->getMessage(),
                    'status'          => false,
                    'identifier_code' => 186002,
                    'status_code'     => 400,
                    'message'         => $this->stripeErrorHandler($ex->getJsonBody())
                ];
            }
        }
        else
        {
            return [
                'data'            => NULL,
                'status'          => false,
                'identifier_code' => 186003,
                'status_code'     => 400,
                'message'         => "This card can't be deleted, You should have at least on payment method"
            ];
        }
        

    }

    // Delete Payment Method
    public function getDefaultPaymentMethod(){
        // configure strip
        $stripe = new \Stripe\StripeClient(
            config('cashier.secret')
        );

        try
        {
            $source =  Auth::user()->defaultPaymentMethod();
            if($source)
            {
                return [
                    'data' => $source,
                    'status' => true,
                    'identifier_code' => 216001,
                    'status_code' => 200,
                    'message' => 'Default credit card returned successfully'
                ];
            
            }
            else
            {

                return [
                    'data' => NULL,
                    'status' => false,
                    'identifier_code' => 216002,
                    'status_code' => 400,
                    'message' => 'You don not have default credit card'
                ];
            }
        }
        catch(\Exception $ex)
        {
            Log::error("get default payment method failed due to:",[$ex->getMessage()]);
            return [
                'data' => $ex->getMessage(),
                'status' => false,
                'identifier_code' => 216002,
                'status_code' => 400,
                'message' => $this->stripeErrorHandler($ex->getJsonBody())
            ];
        }

    }

    // Upgrade from trial
    public function upgradeFromTrial() {
        $user = Auth::user();
        $plan = Plan::first();
        if ($user->onTrial())
        {
            if ($user->hasPaymentMethod())
            {
                if ($user->hasDefaultPaymentMethod())
                {
                    try
                    {
                        $process =  $user->subscription('default')->endTrial();
                        return [
                            'data'            => $user->subscription(),
                            'status'          => true,
                            'identifier_code' => 186001,
                            'status_code'     => 200,
                            'message'         => 'Subscription upgraded successfully'
                        ];
                    }
                    catch(\Exception $ex)
                    {
                        Log::error("upgrade subscription failed due to:",[$ex->getMessage()]);
                        return [
                            'data' => $ex->getMessage(),
                            'status' => false,
                            'identifier_code' => 186002,
                            'status_code' => 400,
                            'message' => $this->stripeErrorHandler($ex->getJsonBody())
                        ];
                    }
                }
                else
                {
                    return [
                        'data' => Null,
                        'status' => false,
                        'identifier_code' => 186002,
                        'status_code' => 400,
                        'message' => "You don't have default method, plz add default method to complete the process"
                    ];
                }
            }
            else
            {
                return [
                    'data' => Null,
                    'status' => false,
                    'identifier_code' => 186002,
                    'status_code' => 400,
                    'message' => "You don't have any payment method, plz add payment method to complete the process"
                ];
            }

        }
        else
        {
            $stripe = new \Stripe\StripeClient(
                config('cashier.secret')
            );
            $subscriptions =  $stripe->subscriptions->all([
                'customer' => Auth::user()->stripe_id,
                'status' => "active",
            ]);
            return [
                'data'            => $subscriptions,
                'status'          => false,
                'identifier_code' => 186001,
                'status_code'     => 400,
                'message'         => 'You are already subscribed in the main plan'
            ];
        }
    }

    // cancel current active subscription plan
    public function cancelSubscription(){
        $subscription = Subscription::where("user_id",Auth::user()->id)
                                    ->where(function ($q){
                                        $q->Where("stripe_status","trialing")
                                        ->orWhere("stripe_status","active");
                                    })
                                    ->WhereNull("ends_at")
                                    ->orderBy("id","DESC")
                                    ->with("plan")
                                    ->first();
        if(!is_null($subscription))
        {
            try
            {
                // cancel the subscription
               $subscription->cancelNow();

               // update the status of the subscription
               $subscription->stripe_status = "cancelled";
               $subscription->save();

               // logout user from the system
               auth()->user()->tokens()->delete();
                return [
                    'data' => $subscription,
                    'status' => true,
                    'identifier_code' => 186001,
                    'status_code' => 200,
                    'message' => 'Subscription cancelled successfully'
                ];
            }
            catch(\Exception $ex)
            {
                Log::error("Cancel subscription failed due to:",[$ex->getMessage()]);
                return [
                    'data' => $ex->getMessage(),
                    'status' => false,
                    'identifier_code' => 186002,
                    'status_code' => 400,
                    'message' => $this->stripeErrorHandler($ex->getJsonBody())
                ];
            }
        }
        else
        {
            return [
                'data' => NULL,
                'status' => false,
                'identifier_code' => 186002,
                'status_code' => 400,
                'message' => "Failed to get your current subscription"
            ];
        }

    }

    // Check if card exist in stripe before or not
    public function checkCardExistBefore($user,$token){
        $stripe = new \Stripe\StripeClient(
            config('cashier.secret')
        );

        $response = $stripe->tokens->retrieve(
            $token,
            []
        );
        $fingerprint  = $response->card->fingerprint;
        $cards        = $stripe->paymentMethods->all(['customer' => $user->stripe_id , 'type' => 'card']);
        $fingerprints = [];

        foreach ($cards as $card) {
            array_push($fingerprints,$card['card']['fingerprint']);
        }
        return in_array($fingerprint, $fingerprints, true) ? true : false;

    }

    public function retrieveCardData($data)
    {
        $stripe = new \Stripe\StripeClient(
            config('cashier.secret')
        );

        try{
            $source = $stripe->customers->retrieveSource(
                        Auth::user()->stripe_id,
                        $data['card_id'],
                        []
                    );

            return [
                'data' => $source,
                'status' => true,
                'identifier_code' => 194001,
                'status_code' => 200,
                'message' => 'Credit Card data'
            ];

        }catch(\Exception $ex)
        {
            Log::error("retrieve payment method failed due to:",[$ex->getMessage()]);
            return [
                'data' => $ex->getMessage(),
                'status' => false,
                'identifier_code' => 194002,
                'status_code' => 400,
                'message' => $this->stripeErrorHandler($ex->getJsonBody())
            ];
        }



    }
    
}

