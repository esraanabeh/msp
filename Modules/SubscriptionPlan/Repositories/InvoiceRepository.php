<?php

namespace Modules\SubscriptionPlan\Repositories;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Modules\Authentication\Http\Resources\UserResource;
use Modules\SubscriptionPlan\Http\Resources\PlanResource;
use Modules\SubscriptionPlan\Models\Plan;
use Modules\SubscriptionPlan\Traits\ErrorHandler;
use Illuminate\Support\Facades\Auth;
use Modules\SubscriptionPlan\Http\Resources\InvoiceResource;
use Modules\SubscriptionPlan\Http\Resources\ListInvoicesResource;
use Modules\SubscriptionPlan\Http\Resources\PreviewInvoiceResource;
use Modules\SubscriptionPlan\Repositories\Interfaces\IInvoiceRepository;
use App\Http\Helpers\Helper;

class InvoiceRepository implements IInvoiceRepository{
    use ErrorHandler;

    // List all invoices
    public function list()
    {

        // get all paid invoices
        $allInvoices = Auth::user()->invoices("false",[
            'status'=>"paid"
        ]);

        // filter invoices to exclude free invoices 
        $filtered_collection = $allInvoices->filter(function ($item) {
            return $item->amount_paid > 0;
        })->values();
            return [
                'data'            => ListInvoicesResource::collection(Helper::paginate($filtered_collection,config('app.per_page')))->response()->getData(true),
                'status'          => true,
                'identifier_code' => 160001,
                'status_code'     => 200,
                'message'         => 'List of invoices'
            ];

        }

    // Preview invoice
    public function preview()
    {
        // configure stripe 
        $stripe = new \Stripe\StripeClient(
            config('cashier.secret')
        );
       
        // get invoice 
        $invoice = Auth::user()->findInvoice(request()->invoiceId);
        if($invoice)
        {
            if(!is_null($invoice->payment_intent))
            {
                // get payment details
                $payment_details = $stripe->paymentIntents->retrieve(
                    $invoice->payment_intent,
                    []
                    );
                $invoice->payment_details = $payment_details['charges']['data'][0]['payment_method_details'];
                return [
                    'data' => New PreviewInvoiceResource($invoice),
                    'status' => true,
                    'identifier_code' => 160001,
                    'status_code' => 200,
                    'message' => 'List of invoices'
                ];
            }else{
                return [
                    'data' => NULL,
                    'status' => false,
                    'identifier_code' => 160002,
                    'status_code' => 400,
                    'message' => 'Invoice is not piad'
                ];
            }
            
        }
        else
        {
            return [
                'data' => NULL,
                'status' => false,
                'identifier_code' => 160001,
                'status_code' => 400,
                'message' => 'Invoice is not exist'
            ];
        }
        

    }

}

