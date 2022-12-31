<?php

namespace Modules\Industries\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Organization\Models\Organization;
use Spatie\Activitylog\Traits\LogsActivity;

class Industry extends Model
{
    use HasFactory , LogsActivity;

    protected static $logAttributes =['title'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $fillable = [
        'title',
       'organization_id',

        ];


        public function organization()
        {
            return  $this->belongsTo(Organization::class, 'organization_id', 'id');
        }
}
