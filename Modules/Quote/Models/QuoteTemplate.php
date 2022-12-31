<?php

namespace Modules\Quote\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;

class QuoteTemplate extends Model
{
    use HasFactory ,LogsActivity;
    protected static $logAttributes =[  'introduction',
    'organization_id'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $fillable = [
        'introduction',
        'organization_id'
    ];

    public function sections()
    {
        return $this->hasMany(QuoteSection::class)->orderBy('order','ASC');
    }


}
