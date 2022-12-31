<?php

namespace Modules\FAQ\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class FAQ extends Model
{
    use HasFactory  , SoftDeletes, LogsActivity;
    protected static $logAttributes =[ 'question',
    'category_id',
    'answer',];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $table='f_a_qs';

    protected $fillable = [
        'question',
        'category_id',
        'answer',
        'display_order',
        'deleted_at',

        ];


        public function category()
        {
            return  $this->belongsTo(Category::class, 'category_id', 'id');
        }
}
