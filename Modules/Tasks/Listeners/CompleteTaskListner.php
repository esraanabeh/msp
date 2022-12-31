<?php

namespace Modules\Tasks\Listeners;
use Auth;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Tasks\Events\CompleteTaskEvent;
use Modules\Tasks\Notifications\CompleteTaskNotification;
use App\Models\CustomNotification;
use Notification;


use DB;



class CompleteTaskListner

{
    /**
     * Create the event listener.
     *
     * @return void
     */

    public function __construct()
    {


    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CompleteTaskEvent  $event )
    {
        

        $type= DB::table('notifications')->where('notification_key','Member_Complete_Task')->first();
        $status=DB::table('organization_notifications')->where('notification_id',$type->id)->where("organization_id",Auth::user()->organization_id)->first();


        if ($event->task['data']->done==true){
            if($status->status == 1){
           foreach($event->user as $user){

            $user->notify(new CompleteTaskNotification(auth()->user(),$event->task['data']->task ,$event->taskId));
           }

        }

        }

    }
}
