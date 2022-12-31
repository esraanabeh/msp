<?php

namespace Modules\Client\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Question\Models\Question;
use Modules\Services\Models\Service;
use Spatie\Activitylog\Traits\LogsActivity;

class ClientQuestion extends Model
{

    use HasFactory , LogsActivity;
    protected static $logAttributes =[   'question',
    'client_quote_id',
    'client_id',
    'organization_id',
    'service_id',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $table = "client_questions";
    protected $fillable = [
        'question',
        'client_quote_id',
        'client_id',
        'organization_id',
        'service_id',

    ];



    public function client()
    {
        return $this->belongsTo(Client::class);
    }

     public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function client_quote()
    {
        return $this->belongsTo(ClientQuote::class);
    }
}
