<?php

namespace Modules\Client\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use App\Channels\FcmChannel;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CustomNotification;
use App\Channels\DatabaseChannel;

class AcceptQuoteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

     protected $user;

     protected $client;


    public function __construct($user , $client )
    {
        $this->user = $user;
        $this->client = $client;


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
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase()
    {


           $type= DB::table('notifications')->where('notification_key','Client_Quote_Acceptance')->first();
           $id=$this->client['data']->id;
           $url = 'clients/details?clientId='.$id.'&page=1';
               return[

                   'data'=>[
                       'avatar'=>asset('avatar/no.png'),
                       'client'=>$this->client['data']->contact_person,
                       'email'=>$this->client['data']->email,
                       'event'=>'Accepted Your Quote',
                       'View'    =>$url

                   ],
                   'notification_key'=> $type->notification_key
                ];


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
                    ->line('MSB.')
                    ->greeting('Hello!')
                    ->line('Client'.$this->client['data']->contact_person)
                    ->line('Client'.$this->client['data']->email)
                    ->line('Accepted Your Quote')
                    ->line('Thank you for using our application!');
    }




}
