<?php

namespace Modules\Quote\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;

class QuoteSection extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia ,LogsActivity, SoftDeletes;
    protected static $logAttributes =[  'title',
    'content',
    'order',
    'quote_template_id'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }


    protected $fillable = [
        'title',
        'content',
        'order',
        'quote_template_id'
    ];

    public function quoteTemplate()
    {
        return $this->belongsTo(QuoteTemplate::class,'quote_template_id');
    }


}
