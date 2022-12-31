<?php

namespace Modules\Notifications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class Notification extends Model
{
    use HasFactory ,LogsActivity;
    protected static $logAttributes =['title'];

    protected $fillable = [

        'title',
        'notification_key'
        ];

        public function organization_notification()
    {
     return $this->hasMany(OrganizationNotification::class, 'notification_id');
    }

    public function custom_notification()
    {
     return $this->hasMany(CustomNotification::class, 'notification_key');
    }
}
