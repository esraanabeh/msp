<?php

namespace Modules\Question\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Client\Models\ClientQuestion;
use Modules\Services\Models\Service;
use Modules\Client\Models\Client;
use Spatie\Activitylog\Traits\LogsActivity;

class Question extends Model
{
    use HasFactory ,LogsActivity;
    protected static $logAttributes =['question',
    'type',
    'service_id',
    'organization_id'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $fillable = [
        'question',
        'type',
        'service_id',
        'organization_id',
        'client_id',
    ];

    public function clientsQuestions()
    {
        return $this->hasMany(ClientQuestion::class,);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }


}
