<?php

namespace Modules\SubscriptionPlan\Notifications;

use App\Channels\DatabaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use DB;
use Illuminate\Support\Facades\Log;

class TrialEndReminder extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [DatabaseChannel::class,'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Subscription Reminder')
                    ->line('Your Trial Subscription Period About to End')
                    ->line('your trial will end on : ' . $this->data['end_date']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */

    public function toDatabase()
    {
        $type= DB::table('notifications')->where('notification_key','trial_end_reminder')->first();
               return[

                   'data'=>[
                        'message'=>'your trial period about to end',
                        'end_date' => $this->data['end_date'],
                        'event'=>'Trial End Reminder',
                   ],
                   'notification_key' => $type->notification_key
                ];
    }
}
