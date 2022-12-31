<?php

namespace Modules\FAQ\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use HasFactory  , SoftDeletes , LogsActivity;
    protected static $logAttributes =['name',
    'deleted_at',
    'organization_id'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $fillable = [
        'name',
        'deleted_at',
        'organization_id'

        ];



        public function FAQ()
        {
            return $this->hasMany(FAQ::class,);
        }

        protected static function boot() {
            parent::boot();

            static::deleted(function ($category) {
              $category->FAQ()->delete();
            });
          }
}
