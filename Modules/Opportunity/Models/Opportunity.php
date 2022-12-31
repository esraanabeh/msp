<?php

namespace Modules\Opportunity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Client\Models\Client;
use Modules\Services\Models\Service;
use Spatie\Activitylog\Traits\LogsActivity;


class Opportunity extends Model
{
    use HasFactory ,LogsActivity;
    protected static $logAttributes =['answer','question','opportunity_notes'];

    protected $table = "opportunities";
    protected $fillable = [
        'client_id',
        'service_id',
        'organization_id',
        'notes',
        'qty',
        'total_amount'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
