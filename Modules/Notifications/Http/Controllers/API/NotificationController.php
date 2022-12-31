<?php

namespace Modules\Notifications\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Notifications\Http\Requests\NotificationRequest;
use App\Http\Traits\ApiResponseTrait;
use Modules\Notifications\Models\Notification;
use Modules\Notifications\Http\Resources\NotificationstatusResource;
use Modules\Notifications\Repositories\Repos\NotificationRepository;
use Modules\Organization\Models\Organization;
use Illuminate\Routing\Controller;

class NotificationController extends Controller
{

    use ApiResponseTrait;

    private $notificationRepository;
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $result= $this->notificationRepository->getNotifications();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('notifications::create');
    }

    public function listNotifications()
    {
        $result= $this->notificationRepository->listAdminNotifications();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function NotificationsDetails(Request $request , $id)
    {
        $result= $this->notificationRepository->previewNotificationsDetails($request , $id);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('notifications::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('notifications::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(NotificationRequest $request)
    {
        if (!auth()->user()->organization_admin) {
            return $this->apiResponse( 'this user is not organization admin so can not update status','false','119001',403,'error' );
         }
         else{
         $result= $this->notificationRepository->updateNotifications($request->validated());
         return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
