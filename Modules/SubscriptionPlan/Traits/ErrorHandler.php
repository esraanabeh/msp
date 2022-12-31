<?php
namespace Modules\SubscriptionPlan\Traits;


trait ErrorHandler
{

    public function stripeErrorHandler($exception)
    {
        switch($exception['error']['code']){
            case 'resource_missing':
                return 'You have no attached payment source or default payment method. Please consider adding a default payment method';
                break;
            default:
                return $exception['error']['message'];
                break;
        }

    }
}
?>
