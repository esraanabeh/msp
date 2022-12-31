<?php

namespace Modules\SubscriptionPlan\Models;

use Illuminate\Database\Eloquent\Model;

class PlanFeature extends Model
{
    protected $table = "plans_features";
    protected $fillable = [
        'details',
    ];

    public function Plan(){
        return $this->belongsTo(Plan::class,'plan_id','id');
    }

}
