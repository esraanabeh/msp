<?php

namespace Modules\SubscriptionPlan\Models;

use App\Models\User;
use Modules\SubscriptionPlan\Models\Plan;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Subscription as SubscriptionModel;

class Subscription extends SubscriptionModel
{
    public function user()
    {
        return  $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function plan()
    {
        return  $this->belongsTo(Plan::class, 'stripe_price', 'provider_id');
    }

}
