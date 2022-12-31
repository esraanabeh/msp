<?php

namespace Modules\SubscriptionPlan\Http\Controllers\API;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SubscriptionPlan\Repositories\SubscriptionPlanRepository;
use Modules\SubscriptionPlan\Http\Requests\CreatePaymentMethodRequest;
use Modules\SubscriptionPlan\Http\Requests\UpdatePaymentMethodRequest;
use Modules\SubscriptionPlan\Http\Requests\DeletePaymentMethodRequest;

class PaymentMethodController extends Controller
{
    use ApiResponseTrait;
    protected $stripe;

    public function __construct(SubscriptionPlanRepository $subscription)
    {
        $this->subscription = $subscription;
    }

    public function listPaymentMethods(){
        $result = $this->subscription->listPaymentMethods();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function createPaymentMethod(CreatePaymentMethodRequest $request){
        $result = $this->subscription->createPaymentMethod($request->validated());
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function updatePaymentMethod(UpdatePaymentMethodRequest $request){
        $result = $this->subscription->updatePaymentMethod($request->validated());
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function deletePaymentMethod(DeletePaymentMethodRequest $request){
        $result = $this->subscription->deletePaymentMethod($request->validated());
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function getCreditCardData(DeletePaymentMethodRequest $request){
        $result = $this->subscription->retrieveCardData($request->validated());
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function getDefaultPaymentMethod(){
        $result = $this->subscription->getDefaultPaymentMethod();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }
}
