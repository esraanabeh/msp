<?php

namespace Modules\Notifications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Organization\Models\Organization;
use Spatie\Activitylog\Traits\LogsActivity;

class OrganizationNotification extends Model
{
    use HasFactory,LogsActivity;
    protected static $logAttributes =['status','notification_id'];
    protected $fillable = [

        'organization_id',
        'notification_id',
        'status'
        ];
        public $timestamps = true;

        public function notification()
        {
            return  $this->belongsTo(Notification::class, 'notification_id', 'id');
        }

        public function organization()
        {
            return  $this->belongsTo(Organization::class, 'organization_id', 'id');
        }
}
