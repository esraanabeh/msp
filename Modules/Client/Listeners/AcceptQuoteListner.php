<?php

namespace Modules\Client\Listeners;
use Auth;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Client\Events\AcceptQuoteEvent;
use Modules\Client\Notifications\AcceptQuoteNotification;
use Notification;
use DB;



class AcceptQuoteListner

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
    public function handle(AcceptQuoteEvent  $event )
    {
        $type= DB::table('notifications')->where('notification_key','Client_Quote_Acceptance')->first();
        $status=DB::table('organization_notifications')->where('notification_id',$type->id)->where("organization_id",Auth::user()->organization_id)->first();


        if ($event->client['data']->status =='Active'){
            if($status->status == 1){
                foreach($event->user as $user){
                   
                    $user->notify(new AcceptQuoteNotification(auth()->user(),$event->client));
                }
            }
        }

    }
}
