<?php

namespace App\Channels;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use Illuminate\Notifications\Notification;
use App\Models\User;
use DB;


class DatabaseChannel extends IlluminateDatabaseChannel
{
    /**
    * Send the given notification.
    *
    * @param mixed $notifiable
    * @param \Illuminate\Notifications\Notification $notification
    * @return \Illuminate\Database\Eloquent\Model
    */
    public function send($notifiable, Notification $notification)
    {

        
        $data = $notification->toDatabase($notifiable);
        return $notifiable->routeNotificationFor('database')->create([
            'id'      => $notification->id,
            'type'    => get_class($notification) ? get_class($notification) : 'type',
            'notification_key'=>  $data['notification_key'],
            'data'    => $data['data'],
            'read_at' => null,
        ]);
    }
}
