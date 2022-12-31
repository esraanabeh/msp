<?php

namespace Modules\Dashboard\Repositories\Repos;

use Illuminate\Support\Facades\Auth;
use Modules\Templates\Models\Section;
use Modules\Client\Models\Client;
use Modules\Tasks\Models\Task;
use Carbon\Carbon;
use Modules\Dashboard\Http\Resources\DashboardDetailsResource;
use Modules\Dashboard\Http\Resources\TasksCountResource;
use Modules\Dashboard\Repositories\Interfaces\IDashboardRepository;
use Modules\Tasks\Models\ClientTask;

class DashboardRepository implements IDashboardRepository{


//  get admin tasks on dashboard statistics
public function getStatisticsDashboard(): array
{
    $is_member=false;

    $value = request('sort') === 'open-tasks' ? false : true;  // is_complete


    $filterDate = request('filter');  // filter for date =====  request('filter')

    //count of open tasks with filter
    $open_tasks = ClientTask::whereHas('client',function ($q){
        $q->where('organization_id',Auth::user()->organization_id);
    })
    ->where(function($query)use ($filterDate){
        $query->tasks(false,$filterDate);
    })->count();


   //count of closed tasks with filter
    $closed_tasks = ClientTask::whereHas('client',function ($q){
        $q->where('organization_id',Auth::user()->organization_id);
    })
    ->where(function($query)use ($filterDate){
        $query->tasks(true,$filterDate);
    })->count();



 //list CLient With their sections
    $data = Client::whereOrganizationId(Auth::user()->organization_id)
         ->whereHas('templates',function($query)use ($value,$filterDate){
            $query->whereHas('sections',function($query)use ($value,$filterDate){
                $query->whereHas('client_sections',function($query)use ($value,$filterDate){
                    $query->where('is_completed',$value)
                    ->SearchClientOrTemplate(request('search'))
                    ->where(function($query)use ($filterDate){
                        $query->Sections(true,$filterDate);
                    });
                });
                });
         })
        ->with('templates', function ($query) use ($value,$filterDate ){

            $query->whereHas('sections',function($query)use ($value,$filterDate){
                $query->whereHas('client_sections',function($query)use ($value,$filterDate){
                    $query->where('is_completed',$value)
                    ->SearchClientOrTemplate(request('search'))
                    ->where(function($query)use ($filterDate){
                        $query->Sections(true,$filterDate);
                    });
                });
            })->with('sections',function($query) use ($value,$filterDate) {
                $query->whereHas('client_sections',function($query) use ($value,$filterDate) {
                    $query->where('is_completed',$value)
                    ->SearchClientOrTemplate(request('search'))
                    ->where(function($query)use ($filterDate){
                        $query->Sections(true,$filterDate);
                    });

                });
            });
        });


    $data->orderBy('id','DESC');

    $section = $data->paginate(config('app.per_page'));
    return [

       'data' =>[
                  'open_tasks' => [$open_tasks],
                  'closed_tasks' => [$closed_tasks],
                  'client_sections'=> DashboardDetailsResource::collection($section)->response()->getData(true),
       ],

        'status' => true,
        'identifier_code' => 213001,
        'status_code' => 200,
        'message' => 'Dashboard Statistics Listed successfully'
    ];



}


//  get member tasks on dashboard statistics
public function getMemberStatisticsDashboard(): array
{


    $value = request('sort') === 'open-tasks' ? false : true;  // is_complete


    $filterDate = request('filter');  // filter for date =====  request('filter')

    //count of open tasks with filter
    $open_tasks = ClientTask::whereHas('client',function ($q){
        $q->where('organization_id',Auth::user()->organization_id);
    })
        ->whereHas('task',function($query){
            $query ->whereHas('section',function($query){
                $query->whereHas('team',function($query){
                    $query->whereHas('members',function($query){
                        $query->where('user_id',Auth::user()->id);
                    });
                });
        });

    })
    ->where(function($query)use ($filterDate){
        $query->tasks(false,$filterDate);
    })->count();



   //count of closed tasks with filter
    $closed_tasks = ClientTask::whereHas('client',function ($q){
        $q->where('organization_id',Auth::user()->organization_id);
    })
    ->whereHas('task',function($query){
        $query ->whereHas('section',function($query){
            $query->whereHas('team',function($query){
                $query->whereHas('members',function($query){
                    $query->where('user_id',Auth::user()->id);
                });
            });
    });

    })
    ->where(function($query)use ($filterDate){
        $query->tasks(true,$filterDate);
    })->count();



 //list CLient With their sections
    $data = Client::whereOrganizationId(Auth::user()->organization_id)
         ->whereHas('templates',function($query)use ($value,$filterDate){
            $query->whereHas('sections',function($query)use ($value,$filterDate){
                $query->whereHas('team',function($query){
                    $query->whereHas('members',function($query){
                       $query->where('user_id',Auth::user()->id);
                    });
                })
                ->whereHas('client_sections',function($query)use ($value,$filterDate){
                    $query->where('is_completed',$value)
                    ->SearchClientOrTemplate(request('search'))
                    ->where(function($query)use ($filterDate){
                        $query->Sections(true,$filterDate);
                    });
                });
                });
         })
        ->with('templates', function ($query) use ($value,$filterDate ){

            $query->whereHas('sections',function($query)use ($value,$filterDate){
                $query->whereHas('team',function($query){
                    $query->whereHas('members',function($query){
                       $query->where('user_id',Auth::user()->id);
                    });
                })
                ->whereHas('client_sections',function($query)use ($value,$filterDate){
                    $query->where('is_completed',$value)
                    ->SearchClientOrTemplate(request('search'))
                    ->where(function($query)use ($filterDate){
                        $query->Sections(true,$filterDate);
                    });
                });
            })->with('sections',function($query) use ($value,$filterDate) {
                $query->whereHas('team',function($query){
                    $query->whereHas('members',function($query){
                       $query->where('user_id',Auth::user()->id);
                    });
                })
                ->whereHas('client_sections',function($query) use ($value,$filterDate) {
                    $query->where('is_completed',$value)
                    ->SearchClientOrTemplate(request('search'))
                    ->where(function($query)use ($filterDate){
                        $query->Sections(true,$filterDate);
                    });

                });
            });
        });


    $data->orderBy('id','DESC');

    $section = $data->paginate(config('app.per_page'));
    return [

       'data' =>[
                  'open_tasks' => [$open_tasks],
                  'closed_tasks' => [$closed_tasks],
                  'client_sections'=> DashboardDetailsResource::collection($section)->response()->getData(true),
       ],

        'status' => true,
        'identifier_code' => 213001,
        'status_code' => 200,
        'message' => 'Dashboard Statistics Listed successfully'
    ];



}






}





?>
