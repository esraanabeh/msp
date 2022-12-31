<?php

namespace Modules\Tasks\Repositories\Repos;

use Carbon\Carbon;
use Modules\Tasks\Models\Task;
use Modules\Tasks\Repositories\Interfaces\ITaskRepository;
use Illuminate\Support\Facades\Hash;
use Modules\Templates\Models\Section;
use Illuminate\Support\Facades\Log;
use Modules\Tasks\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Client\Models\Client;
use Modules\Tasks\Http\Resources\clientTaskResource;
use Modules\Tasks\Events\sectionProgress;
use Modules\Tasks\Events\SendClientEmail;
use Modules\Tasks\Events\SendClientEmailEvent;
use Modules\Tasks\Http\Resources\SectionResource;
use Modules\Team\Models\Team;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;
use Modules\Client\Http\Resources\ClientTaskResource as ResourcesClientTaskResource;
use Modules\Tasks\Models\ClientTask;
use Str;

class TaskRepository implements ITaskRepository{


    public function createTask($data,$sectionId){
        try
        {
            // begin transaction
            DB::beginTransaction();
            $tasks=[];

            // checck section existance
            $section = Section::Where("id",$sectionId)->whereHas("template",function ($q){
                $q->where("organization_id",Auth::user()->organization_id)
                    ->where(function ($q){
                        $q->whereNull("owner_id")
                        ->orWhere("owner_id",Auth::user()->id);
                    });
            })->first();

            // in case section exist
            if ($section) {
                // check if assigned team is exist
                $checkTeamExistance = Team::where("id",$data['team_id'])
                                            ->Where("organization_id",Auth::user()->organization_id)
                                            ->Where("status",1)
                                            ->first();
                if(!is_null($checkTeamExistance))
                {
                    $section->update([
                        'description'        => $data['description'] ?? null,
                        'name'               => $data['name'] ?? null,
                        // 'due_date'           => Carbon::parse($data['due_date'])->toDateTimeString(),
                        'reminder_day'       => $data['reminder_day'] ?? null,
                        'reminder_time'      => $data['reminder_time'] ?? null,
                        'shared_with_client' => $data['shared_with_client'] == 'true' ? 1 : 0,
                        'automatic_reminder' => $data['automatic_reminder'] == 'true' ? 1 : 0,
                        'team_id'            => $data['team_id'],
                    ]);

                    if($data['shared_with_client'] == true){
                        $emailTemplate = $section->emailTemplate;
                        if($emailTemplate){
                            $emailTemplate->update([
                                'title' => $data['email_template']['title'],
                                'content' => $data['email_template']['content']
                            ]);
                        } else {
                            $section->emailTemplate()->create([
                                'title' => $data['email_template']['title'],
                                'content' => $data['email_template']['content']
                            ]);
                        }
                    }
                        $taskOrder = 0;
                    foreach ($data['tasks'] as $task) {
                        if(in_array($task['type'],["short_replies","long_replies"]))
                        {
                            if($task['type'] == "short_replies" && !is_null($task['description']) && strlen($task['description'])>1024){
                                // rollback!!!
                                DB::rollback();
                                return [
                                    'data'   => [],
                                    'status' => false,
                                    'identifier_code' => 100003,
                                    'status_code' => 422,
                                    'message' => "short replies answer cannot be mor than 1024 charachters length",
                                ];


                            }
                            if($task['type'] == "long_replies" && !is_null($task['description']) && strlen($task['description'])>4096){
                                // rollback!!!
                                DB::rollback();
                                return [
                                    'data'   => [],
                                    'status' => false,
                                    'identifier_code' => 100003,
                                    'status_code' => 422,
                                    'message' => "long replies answer cannot be mor than 4096 characters length",
                                ];

                            }
                        }
                        $result = Task::updateOrCreate(['id'=>$task['id'] ?? null], [
                            'title'         => $task['title'],
                            'type'          => $task['type'],
                            'description'   => $task['description'] ?? null,
                            'options'       => key_exists('options', $task) ? json_encode($task['options']) : null,
                            'display_order' => $taskOrder++,
                            'section_id'    => $sectionId,
                        ]);


                        if (isset($task['file']) ) {
                            if(key_exists('id',$task) && key_exists('is_file_updated',$task) && $task['is_file_updated'] == true){
                                $result->clearMediaCollection('file');
                                $data = $task['file'];  // your base64 encoded
                                list($type, $data) = explode(';', $data);
                                list(, $data) = explode(',', $data);
                                list(, $type) = explode(':', $type);
                                list(, $extension) = explode('/', $type);
                                $result->addMediaFromBase64($task['file'])
                                ->usingFileName($task['file_name'].'.'.$extension)
                                ->toMediaCollection('file');
                            } elseif (!key_exists('id',$task)) {
                                $data = $task['file'];  // your base64 encoded
                                list($type, $data) = explode(';', $data);
                                list(, $data) = explode(',', $data);
                                list(, $type) = explode(':', $type);
                                list(, $extension) = explode('/', $type);
                                $result->addMediaFromBase64($task['file'])
                                ->usingFileName($task['file_name'].'.'.$extension)
                                ->toMediaCollection('file');
                            }
                        }

                        $section->fresh();
                        if($section->automatic_reminder && $section->template->template_client->count() > 0){
                            $section->update([
                                'next_reminder' => now()->addDays($section->reminder_day)->setTimeFromTimeString($section->reminder_time)
                            ]);
                        }

                        // array_push($tasks,$result);
                        $tasks[]=$result;
                        if($section->template->type == 'custom'){
                            $templateId=$section->template->id;
                            $client_template=DB::table('template_clients')->where('template_id',$templateId)->first();
                            $clientId=$client_template->client_id;
                            ClientTask::create([
                                'client_id' => $clientId,
                                'task_id' =>$result->id
                            ]);
                        }
                    }
                    // Happy ending :)
                    DB::commit();
                    return [
                        'data' =>TaskResource::collection($tasks),
                        'status' => true,
                        'identifier_code' => 175001,
                        'status_code' => 200,
                        'message' => 'Task created successfully'
                    ];




                }
                else
                {
                    // rollback!!!
                    DB::rollback();
                    return [
                        'data' => null,
                        'status' => false,
                        'identifier_code' => 175002,
                        'status_code' => 400,
                        'message' => 'Assigned team is not exist'
                    ];
                }

            }
            else
            {
                // rollback!!!
                DB::rollback();
                return [
                    'data' => null,
                    'status' => false,
                    'identifier_code' => 175003,
                    'status_code' => 400,
                    'message' => 'Section is not exist'
                ];
            }
        }
        catch (\Exception $e) {
            // rollback!!!
            DB::rollback();
            Log::info("create task issue : ".$e->getMessage());
            return [
                'data'            => NULL,
                'status'          => false,
                'identifier_code' => 175004,
                'status_code'     => 400,
                'message'         => 'Some thing went wrong, please try again later'
            ];
        }

 }



 public function listallTasks($sectionId)
 {

    // checck section existance
    $section = Section::Where("id",$sectionId)->whereHas("template",function ($q){
        $q->where("organization_id",Auth::user()->organization_id);

    })->first();

    if($section){
        $tasks=Task::where('section_id',$sectionId)->orderBy('display_order','DESC');
        $data = $tasks->paginate(config('app.per_page'));
        return [
            'data' =>TaskResource::collection($data)->response()->getData(true),
            'status' => true,
            'identifier_code' => 176001,
            'status_code' => 200,
            'message' => 'tasks listed successfully'
           ];
    }
    else{
        return [
            'data' =>null,
            'status' => false,
            'identifier_code' => 176002,
            'status_code' => 400,
            'message' => 'this section is not existed'
           ];

    }

 }



 public function deleteTask($id)
{

    try
    {
        $tasks = Task::where("id",$id)->whereHas("section",function ($q){
            $q->whereHas("template",function ($qq){
                $qq->where("organization_id",Auth::user()->organization_id)
                    ->where(function ($qq){
                        $qq->whereNull("owner_id")
                        ->orWhere("owner_id",Auth::user()->id);
                    });
            });
        })->first();

        if(!is_null($tasks))
        {
            $tasks->delete();

            $data  = Task::whereHas('section',function($query){
                $query->whereHas('template',function($query){
                $query->whereHas('organization',function($query){
                $query->whereHas('organization_admin',function($query){
                $query->whereUserId(auth()->user()->id);
                });
                });
                });
                })->get();

                return [
                    'data' => TaskResource::collection($data),
                    'status' => true,
                    'identifier_code' => 184001,
                    'status_code' => 200,
                    'message' => 'task deleted successfully'
                ];
        }
        else
        {
            return [
                'data' => NULL,
                'status' => false,
                'identifier_code' => 184002,
                'status_code' => 400,
                'message' => 'The task is not exist'
            ];
        }

    }
    catch(\Exception $ex)
    {
        Log::info("Delete task process failed due to : ".$ex->getMessage());
        return [
            'data' => NULL,
            'status' => false,
            'identifier_code' => 184003,
            'status_code' => 400,
            'message' => 'Some thing went wrong, plz try again later'
        ];
    }

}



public function dublicateTask($request)
{

    $task = Task::where("id",$request['task_id'])->whereHas("section",function ($q){
        $q->whereHas("template",function ($qq){
            $qq->where("organization_id",Auth::user()->organization_id)
                ->where(function ($qq){
                    $qq->whereNull("owner_id")
                    ->orWhere("owner_id",Auth::user()->id);
                });
        });
    })->first();

    if(!is_null($task))
    {
        $new_task = $task->replicate();
        $new_task->save();

        return [
         'data' => new TaskResource($new_task),
         'status' => true,
         'identifier_code' => 187001,
         'status_code' => 200,
         'message' => 'tasks dublicated successfully'
        ];
    }
    else
    {
    return [
        'data' => NULL,
        'status' => false,
        'identifier_code' => 187002,
        'status_code' => 400,
        'message' => 'The task is not exist'
    ];

    }

}


public function updateProgress($taskId)
{
    $task = ClientTask::whereId($taskId)->whereHas('task',function($query){
        $query->whereHas('section',function($query){
            $query->whereHas('template',function($query){
                $query->where("organization_id",Auth::user()->organization_id);
            });
        });

    })
        ->first();
    // dd($task);

    if(!$task)
    {
      return [
        // dd('dfd'),
         'data' => null,
         'status' => false,
         'identifier_code' => 203002,
         'status_code' => 400,
         'message' => 'this Task is not existed'
        ];
    }
    else
    {
        $task->update([
            'done' => ! $task->done,
            // 'done' => $request->post('done') == 'true' ? 1 : 0,
        ]);
        if($task->done==1){
            $task->update([
                'status' => 'done',
            ]);
        }
        else{
            $task->update([
                'status' => 'open',
            ]);

        }

        event(new sectionProgress($task));

        return [
            'data' => $task->refresh(),
            'status' => true,
            'identifier_code' => 203001,
            'status_code' => 200,
            'message' => 'this Task is Completed successfully'
            ];
    }

 }

 // send email to the client
public function sendEmail($request){
    // check email exist
    $checkEmailExsitence = Client::where("email",$request->email)
                            ->where("organization_id",Auth::user()->organization_id)
                            ->first();
    if($checkEmailExsitence)
    {
        try
        {
            event(new SendClientEmailEvent($request));
            return [
                'data' => NULL,
                'status' => true,
                'identifier_code' => 208001,
                'status_code' => 200,
                'message' => 'Email sent successfully'
                ];
        }
        catch (\Exception $e)
        {
            Log::info("send email to client issue : ".$e->getMessage());
            return [
                'data'            => NULL,
                'status'          => false,
                'identifier_code' => 208003,
                'status_code'     => 400,
                'message'         => 'Some thing went wrong, please try again later'
            ];
        }
    }
    else
    {
        return ['data'            => null,
                'status'          => false,
                'identifier_code' => 208002,
                'status_code'     => 400,
                'message'         => 'this Client is not existed'];
    }

}
  // show section details
  public function listclientTasks($request ,$sectionId)
  {
      // check section existance
      $section = Section::Where("id",$sectionId)->whereHas("template",function ($q){
          $q->where("organization_id",Auth::user()->organization_id)
              ->where(function ($q){
                  $q->whereNull("owner_id")
                  ->orWhere("owner_id",Auth::user()->id);
              });
      })->first();

       $client=$request->post('client_id');
       $tasks=$section->whereHas('template',function($query) use ($client){
        $query->whereHas('template_client',function($q) use ($client){
            $q->where('client_id',$client);
        });
    })->get();


      // in case section exist
      if($section){

          return [
              'data'            => SectionResource::collection($tasks),
              'status'          => true,
              'identifier_code' => 209001,
              'status_code'     => 200,
              'message'         => 'client tasks listed Successfully'
          ];
      }
      // in case section is not exist
      else
      {
          return [
              'data'            => null,
              'status'          => false,
              'identifier_code' => 209002,
              'status_code'     => 400,
              'message'         => 'section is not exist'
          ];
      }
  }

  public function editTasks($data)
  {
    $tasks =[];
    foreach($data['tasks'] as $task){
        $temp = ClientTask::find($task['id']);
        $temp->answer = $task['answer'] ?? null;
        $temp->done = 1;
        $temp->status = 'done';
        $temp->save();
        if (isset($task['file']) ) {
            if(key_exists('id',$task) && key_exists('is_file_updated',$task) && $task['is_file_updated'] == true){
                $temp->clearMediaCollection('file');
                $data = $task['file'];  // your base64 encoded
                list($type, $data) = explode(';', $data);
                list(, $data) = explode(',', $data);
                list(, $type) = explode(':', $type);
                list(, $extension) = explode('/', $type);
                $temp->addMediaFromBase64($task['file'])
                ->usingFileName($task['file_name'].'.'.$extension)
                ->toMediaCollection('file');
            } elseif (!key_exists('id',$task)) {
                $data = $task['file'];  // your base64 encoded
                list($type, $data) = explode(';', $data);
                list(, $data) = explode(',', $data);
                list(, $type) = explode(':', $type);
                list(, $extension) = explode('/', $type);
                $temp->addMediaFromBase64($task['file'])
                ->usingFileName($task['file_name'].'.'.$extension)
                ->toMediaCollection('file');
            }
        }
        array_push($tasks,$temp);
    }

    event(new sectionProgress($tasks[0]));

    return [
        'data'            => ResourcesClientTaskResource::collection($tasks),
        'status'          => true,
        'identifier_code' => 209001,
        'status_code'     => 200,
        'message'         => 'client tasks listed Successfully'
    ];
  }

  public function deleteClientTasks($data)
  {
    ClientTask::whereIn('id',$data['tasks'])->delete();
    return [
        'data'            => null,
        'status'          => true,
        'identifier_code' => 223001,
        'status_code'     => 200,
        'message'         => 'tasks deleted successfully'
    ];
  }





}




?>
