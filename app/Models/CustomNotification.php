<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Notifications\Models\Notification;

class CustomNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'notification_key',
        'notifiable',
        'data',
        'read_at',
    ];
    protected $casts = [
        'data' => 'array',
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function notification()
    {
        return  $this->belongsTo(Notification::class,'notification_key','id');
    }
}
