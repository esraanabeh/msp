<?php

namespace Modules\Notifications\Repositories\Repos;
use Modules\Notifications\Models\Notification;
use Modules\Notifications\Http\Resources\NotificationResource;
use Modules\Notifications\Http\Resources\CustomNotificationResource;
use Modules\Notifications\Repositories\Interfaces\INotificationRepository;
use Illuminate\Support\Facades\Auth;
use Modules\Notifications\Models\OrganizationNotification;
use App\Models\CustomNotification;
use Carbon\Carbon;

class NotificationRepository implements INotificationRepository{



    public function getNotifications()
    {
        $notifications= OrganizationNotification::where('organization_id', Auth::user()->organization_id)
                                                ->with('notification')
                                                ->get();
        return [
            'data' => NotificationResource::collection($notifications),
            'status' => true,
            'identifier_code' => 118001,
            'status_code' => 200,
            'message' => 'notification listed successfully'
        ];
    }



    public function updateNotifications($data)
    {
        foreach ($data['settings'] as $notification) {
            OrganizationNotification::whereId($notification['id'])->update(['status' => $notification['status']]);
        }
        return [
            'data' => null,
            'status' => true,
            'identifier_code' => 118001,
            'status_code' => 200,
            'message' => 'notification updated successfully'
        ];
    }


    public function listAdminNotifications()
    {
        $data= CustomNotification::where('notifiable_id',auth()->user()->id)->orderBy("id","DESC");
        $notifications = isset(request()->paginate) && request()->paginate == "false" ?  $data->get() : $data->paginate(config('app.per_page')) ;

        return [
            'data' => CustomNotificationResource::collection($notifications)->response()->getData(true),
            'status' => true,
            'identifier_code' => 211001,
            'status_code' => 200,
            'message' => 'notification listed successfully'
        ];
    }


    public function previewNotificationsDetails($request,$id)
    {

        $notification= CustomNotification::whereId($id)->where('notifiable_id',auth()->user()->id)->first();
        if($notification){
            $notification->update([
                'read_at'=> Carbon::now()
            ]);

            return [
                'data' => new CustomNotificationResource($notification),
                'status' => true,
                'identifier_code' => 212001,
                'status_code' => 200,
                'message' => 'notification updated successfully'
            ];
        }

        else{
            return [
                'data' => null,
                'status' => false,
                'identifier_code' => 212002,
                'status_code' => 400,
                'message' => 'this notification is not existed'
            ];

        }


    }



}



?>
