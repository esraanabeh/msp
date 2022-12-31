<?php

namespace Modules\SubscriptionPlan\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Plan extends Model
{
    use  LogsActivity;
    protected static $logAttributes =['name',
    'currency',
    'yearly_price',
    'status',];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }
    
    protected $table = "plans";
    protected $fillable = [
        'name',
        'currency',
        'yearly_price',
        'status',
    ];

    public function Features(){
        return $this->hasMany(PlanFeature::class, 'plan_id');
    }

}
