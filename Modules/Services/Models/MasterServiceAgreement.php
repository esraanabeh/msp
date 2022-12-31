<?php

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;
use Spatie\Activitylog\Traits\LogsActivity;
use  Modules\Organization\Models\Organization;

class MasterServiceAgreement extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes, LogsActivity;
    protected static $logAttributes =[ 'title',
    'organization_id',
    'isDefault',];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $fillable = [
        'title',
        'organization_id',
        'isDefault',
        ];

    protected $cast =[
        'isDefault'=>'boolean'
    ];

        /**
     * @return string
     */

        public function getImageAttribute($value)
        {
            if($value) return url($value);

            return $value;
        }

        public function getCoverAttribute(): string
        {
            return $this->getFirstMediaUrl('avatar')  ;
        }


        public function getFileAttribute()
        {
            return $this->getFirstMedia('file');
        }


    /**
         * @var string[]
         */
        protected $appends= [
            'cover',
            'file'
        ];


        public function organization()
        {
            return  $this->belongsTo(Organization::class, 'organization_id', 'id');
        }
}
