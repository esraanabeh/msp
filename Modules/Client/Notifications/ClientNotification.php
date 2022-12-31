<?php

namespace Modules\Client\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use App\Channels\FcmChannel;
use Auth;
use DB;

class ClientNotification extends Notification implements ShouldQueue
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
        $this->createNotification();
        return ['database', FcmChannel::class];
    }




    private function createNotification(){
        $or_admins=User::whereHas('organization_admin',function($q){
         $q->where('organization_id',$this->user->organization_id);
        })->where('id', '!=', $this->user->id)->get();
        $type= DB::table('notifications')->where('notification_key','Client_Quote_Acceptance')->first();
        foreach( $or_admins as  $or_admin)  {
            $or_admin->notifications()->create([

                'notification_key'=> $type->notification_key,

                'data'=>[
                    'avatar'=>$this->user->getFirstMediaUrl('avatar'),
                    'name'=>$this->user->first_name.' '.$this->user->last_name ,
                    'email'=>$this->user->email . ' created client',
                    'client'=>$this->client['data']['client']->contact_person
                ]

            ]);

        }


    }


    // public function toFcm($notifiable)
    // {
    //     FCMAction::new($notifiable)->withData($this->toArray($notifiable))
    //         ->withBody($this->message)
    //         ->sendMessage('tokens');
    // }
}
