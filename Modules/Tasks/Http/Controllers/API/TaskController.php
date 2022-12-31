<?php

namespace Modules\Tasks\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Tasks\Http\Requests\TaskRequest;
use Modules\Tasks\Http\Requests\DeleteTaskRequest;
use Modules\Tasks\Http\Requests\DublicateRequest;
use Modules\Tasks\Http\Requests\ProgressRequest;
use Modules\Tasks\Http\Requests\ClientTaskRequest;
use Modules\Tasks\Repositories\Repos\TaskRepository;
use Modules\Tasks\Events\CompleteTaskEvent;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Routing\Controller;
use App\Models\User;
use Modules\Tasks\Http\Requests\DeleteClientTasksRequest;
use Modules\Tasks\Http\Requests\SendEmailRequest;
use Modules\Tasks\Http\Requests\UpdateClientTaskRequest;

class TaskController extends Controller
{
    use ApiResponseTrait;

    private $taskRepository;
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function index()
    {
        return view('tasks::index');
    }


    public function listTasks($sectionId)
    {
        $result = $this->taskRepository->listallTasks($sectionId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function listClientTasks(ClientTaskRequest $request,$sectionId)
    {
        $result = $this->taskRepository->listclientTasks( $request ,$sectionId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('tasks::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(TaskRequest $request,$sectionId)
    {
        $result = $this->taskRepository->createTask($request->validated(),$sectionId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('tasks::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('tasks::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function updateTask($taskId)
    {

        $result = $this->taskRepository->updateProgress($taskId);
        if($result['status_code']==200){
            $or_users=User::where('organization_id',auth()->user()->organization_id)
            ->where('id', '!=', auth()->user()->id)->where('status',1)->where('is_verified',1)->get();
           event(new CompleteTaskEvent($result,$or_users,$taskId));
        }
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */

    public function delete($id)
    {
        $result= $this->taskRepository->deleteTask($id);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }


    public function dublicate(DublicateRequest $request)
    {
        $result= $this->taskRepository->dublicateTask($request);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function sendEmail(SendEmailRequest $request)
    {
        $result= $this->taskRepository->sendEmail($request);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function update(UpdateClientTaskRequest $request)
    {
        $result= $this->taskRepository->editTasks($request->validated());
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function deleteClientTasks(DeleteClientTasksRequest $request)
    {
        $result= $this->taskRepository->deleteClientTasks($request->validated());
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

}
