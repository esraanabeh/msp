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
use Modules\SubscriptionPlan\Repositories\InvoiceRepository;

class InvoiceController extends Controller
{
    use ApiResponseTrait;
    protected $stripe;

    public function __construct(InvoiceRepository $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }



    public function list(Request $request)
    {
        $result = $this->invoiceRepository->list();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function preview(Request $request)
    {
        $result = $this->invoiceRepository->preview();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }
}
