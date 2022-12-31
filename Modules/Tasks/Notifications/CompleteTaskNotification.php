<?php

namespace Modules\Tasks\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use App\Channels\FcmChannel;
use Auth;
use App\Channels\DatabaseChannel;
use Illuminate\Support\Facades\DB;
use App\Models\CustomNotification;
use Modules\Templates\Models\Section;
use Modules\Tasks\Models\ClientTask;

class CompleteTaskNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

     protected $user;

     protected $task;


    public function __construct($user , $task ,$taskId )
    {

        $this->user = $user;
        $this->task = $task;
        $this->taskId = $taskId;


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
            $client=ClientTask::whereId($this->taskId)->where('task_id',$this->task->id)->first();
            $client_id=$client->client_id;
            $template=$this->task->section->template->title;
            $id=$this->task->section->id;
            $type= DB::table('notifications')->where('notification_key','Member_Complete_Task')->first();
            $url = '/clients/'.$client_id.'/section/'.$id;


                return[

                        'notification_key'=> $type->notification_key,
                        'data'=>[
                            'avatar'=>$this->user->getFirstMediaUrl('avatar'),
                            'member'=>$this->user->first_name.' '.$this->user->last_name,
                            'email'=>$this->user->email,
                            'event'=>'Completed task In',
                            'Template'=>$template,
                            'View'    =>$url
                        ]

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


        $template=$this->task->section->template->title;


        return (new MailMessage)

                    ->greeting('Hello!')
                    ->line($this->user->first_name.' '.$this->user->last_name.' '.'Completed task In'.' '.'Docoment'.' '.$template)
                    ->line('Thank you for using our application!');


    }




}
