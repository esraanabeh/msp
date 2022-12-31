<?php

namespace Modules\Team\Repositories\Repos;
use Modules\Team\Models\Team;
use Carbon\Carbon;
use Modules\Team\Repositories\Interfaces\ITeamRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Modules\Team\Http\Resources\TeamResource;

class TeamRepository implements ITeamRepository{



    public function getTeams()
    {
        $qb = Team::whereHas('organization',function($query){
            $query->whereHas('organization_admin',function($query){
                $query->whereUserId(auth()->user()->id);
            });
        })->when(request()->has('search'),function($q){
            $q->where('name','LIKE','%'.request('search').'%');
        })
        ->when(request()->has('sort'),function($q){
            if(request('sort') == 'z-a'){
                $q->orderBy('name','DESC');
            } else {
                $q->orderBy('name','ASC');
            }
        })
        ->when(request()->has('status')&& !is_null(request('status')) && request('status') != "", function($q){
            if(request('status') == 'Active'){
                $q->where('status',true)->get();
            } elseif(request('status') == 'Deactive') {
                $q->where('status',false)->get();
            }
        });
        $teams = isset(request()->paginate) && request()->paginate == "false" ?  $qb->get() : $qb->paginate(config('app.per_page')) ;
        return [
            'data' => TeamResource::collection($teams)->response()->getData(true),
            'status' => true,
            'identifier_code' => 107004,
            'status_code' => 200,
            'message' => 'List of teams'
        ];
    }


    public function createTeam(  $request){

           $team= Team::create( [
            'organization_id'=>Auth::user()->organization_id,
            'name' => $request->post( 'name' ),
        ] );

        return [
            'data' => new TeamResource($team),
            'status' => true,
            'identifier_code' => 107001,
            'status_code' => 200,
            'message' => 'team created successfully'
        ];

    }


    public function updateTeam(  $id ,  $request){
        $name=Team::find($id);
         $team=$name->update([
            'name' => $request->name,
        ]);
        return [
            'data' => new TeamResource($name),
            'status' => true,
            'identifier_code' => 107002,
            'status_code' => 200,
            'message' => 'team updated successfully'
        ];

    }

 public function deleteTeam($id)
    {
        $team = Team::find($id);
        if(count($team->members) > 0){
            return [
                'data' => null,
                'status' => false,
                'identifier_code' => 143002,
                'status_code' => 400,
                'message' => 'can not delete this team , because it has active members'
            ];

        } else {
            $team->delete();
            return [
                'data' => null,
                'status' => true,
                'identifier_code' => 143001,
                'status_code' => 200,
                'message' => 'team deleted successfully'
            ];
        }

    }

    public function updateTeamStatus(  $request){
        $team=Team::find($request->post('team_id')) ;
        if(!$team){
            return [
                'data' => null,
                'status' => true,
                'identifier_code' => 144002,
                'status_code' => 200,
                'message' => 'this team not exist '
            ];

        }
        if($team){
            if ($request->post('status') == false){
                        if(count($team->active_members) > 0){
                            return [
                                'data' => new TeamResource($team),
                                'status' => false,
                                'identifier_code' => 144003,
                                'status_code' => 400,
                                'message' => 'this team cant be deactived , because it has active members '
                            ];

                        }
                        else{
                            $team->update([
                                'status' => $request->post('status') == 'false' ? : 0,

                                ]);

                                    return [
                                        'data' => new TeamResource($team),
                                        'status' => true,
                                        'identifier_code' => 144001,
                                        'status_code' => 200,
                                        'message' => 'team deactived successfully'
                                    ];
                                }
            }

            else{
                $team->update([
                    'status' => $request->post('status') == 'true' ? : 1,

                    ]);

                        return [
                            'data' => new TeamResource($team),
                            'status' => true,
                            'identifier_code' => 144004,
                            'status_code' => 200,
                            'message' => 'team activated successfully'
                        ];


            }


            }
        }





}



?>
