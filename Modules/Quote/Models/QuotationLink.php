<?php

namespace Modules\Quote\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Client\Models\Client;

class QuotationLink extends Model
{
    use HasFactory,LogsActivity;
    protected static $logAttributes =['code',
    'valid',
    'status',
    'client_id',
    'client_quote_id'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $fillable = [
        'code',
        'valid',
        'status',
        'client_id',
        'client_quote_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class , 'client_id');
    }

    public function quote()
    {
        return $this->belongsTo(ClientQuote::class , 'client_quote_id');
    }


}

?>
