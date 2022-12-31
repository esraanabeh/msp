<?php

namespace Modules\Client\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Organization\Models\Organization;
use Modules\Opportunity\Models\Opportunity;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Notifications\Notifiable;
use Modules\Services\Models\Service;
// use Modules\Opportunity\Models\Opportunity;

class ClientMrrService extends Model
{
    use LogsActivity, Notifiable;

    protected $table = 'client_mrr_services';

    protected static $logAttributes =[
        'client_id',
        'service_id',
        'qty',
        'cost',
        'total_amount'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }


    protected $fillable = [
       'client_id',
       'service_id',
       'qty',
       'cost',
       'total_amount'
    ];

}
