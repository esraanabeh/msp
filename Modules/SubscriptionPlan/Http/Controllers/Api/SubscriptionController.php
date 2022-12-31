<?php

namespace Modules\SubscriptionPlan\Http\Controllers\API;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\SubscriptionPlan\Models\Plan;
use Modules\SubscriptionPlan\Repositories\SubscriptionPlanRepository;
use Laravel\Cashier\Events\WebhookReceived;
use Modules\SubscriptionPlan\Http\Requests\CreatePaymentMethodRequest;

class SubscriptionController extends Controller
{
    use ApiResponseTrait;
    protected $stripe;

    public function __construct(SubscriptionPlanRepository $subscription)
    {
        // $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $this->subscription = $subscription;
    }

    public function webhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        event(new WebhookReceived($payload));
        return $this->apiResponse(null,true,2020,200,'event received successfully');
    }

    public function subscripeTrial(Request $request)
    {
        $result = $this->subscription->subscripeTrial();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }


    public function subscripeYearly(CreatePaymentMethodRequest $request){
        $result = $this->subscription->subscribeYearly($request->validated());
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    
    }


    public function createPaymentMethod(CreatePaymentMethodRequest $request){
        $result = $this->subscription->createPaymentMethod($request->validated());
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    
    }

    public function upgradeFromTrial(){
        $result = $this->subscription->upgradeFromTrial();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    
    }

    public function cancel(){
        $result = $this->subscription->cancelSubscription();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    
    }

    public function createToken(Request $request)
    {
        $stripe = new \Stripe\StripeClient(
            config('cashier.secret')
          );
          $token = $stripe->tokens->create([
            'card' => [
              'number' => $request->card_number,
              'exp_month' => $request->exp_month,
              'exp_year' => $request->exp_year,
              'cvc' => $request->cvc,
            ],
          ]);

          return $this->apiResponse($token,200,123,200,"");
    }


        




    public function storePlan(Request $request)
    {
        $data = $request->except('_token');
        $price = $data['cost'] *100;

        //create stripe product
        $stripeProduct = $this->stripe->products->create([
            'name' => $data['name'],
        ]);

        //Stripe Plan Creation
        $stripePlanCreation = $this->stripe->plans->create([
            'amount' => $price,
            'currency' => 'usd',
            'interval' => 'year', //  it can be day,week,month or year
            'product' => $stripeProduct->id,
        ]);

        $data['stripe_plan'] = $stripePlanCreation->id;

        Plan::create($data);

    }
}
