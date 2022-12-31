<?php

namespace Modules\Quote\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Client\Models\ClientQuestion;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;



class ClientQuote extends Model implements HasMedia
{
    use HasFactory,LogsActivity,InteractsWithMedia;
    protected static $logAttributes =[ 'introduction',
    'services',
    'other_sections',
    'client_id',
    'organization_id',
    'is_sent',
    'status',
    'creator_user_id',
    'editor_user_id',
    'master_service_agreement_id'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $fillable = [
        'introduction',
        'services',
        'other_sections',
        'client_id',
        'organization_id',
        'is_sent',
        'status',
        'creator_user_id',
        'editor_user_id',
        'master_service_agreement_id'
    ];

    protected $casts = [
        'services' => 'array',
        'other_sections' => 'array'
    ];

    public function questions()
    {
        return $this->hasMany(ClientQuestion::class);
    }


}

?>
