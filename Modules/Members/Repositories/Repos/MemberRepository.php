<?php

namespace Modules\Members\Repositories\Repos;
use Modules\Members\Models\Member;
use Carbon\Carbon;
use App\Models\User;
use Modules\Members\Repositories\Interfaces\IMemberRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str ;
use Modules\Members\Http\Resources\MemberResource;
use Modules\Members\Http\Resources\ListMembersResource;
use Modules\Members\Http\Resources\UpdateMemberResource;
use DB;
use Modules\Authentication\Jobs\SendAdminVerificarionMailJob;
use Modules\Authentication\Repositories\Repos\UserRepository;

class MemberRepository implements IMemberRepository{



    public function getMembers()
    {
        $members = User::join('members', 'users.id', '=', 'members.user_id')->whereHas("member",function ($q){
            $q->whereHas('team', function($w){
                $w->where('organization_id',Auth::user()->organization_id);
            });
        });
        if (request()->has('search')){
            $members = $members ->where(function ($qq){
                $qq->where('users.email','LIKE','%'.request('search').'%')
                    ->orwhere(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"),'LIKE','%'.request('search').'%');
            });
        }
        if(request()->has('sort')){
            if(request('sort') == 'z-a'){
                $members =   $members->orderBy('users.first_name','DESC');
            } else {
                $members =   $members->orderBy('users.first_name','ASC');
            }
        }
        $members = $members->select('users.*')
        ->paginate(config('app.per_page'));
        return [
            'data' => ListMembersResource::collection($members)->response()->getData(true),
            'status' => true,
            'identifier_code' => 110004,
            'status_code' => 200,
            'message' => 'List of members of the team'
        ];
    }

    public function createUser(  $request){
        $user =  User::create( [
            'first_name' => $request->post( 'first_name' ),
            'last_name' => $request->post( 'last_name' ),
            'email' => $request->post( 'email' ),
            'status' => $request->post( 'status' ) == 'true' ? 1 : 0,
            'role' => $request->post( 'role' ),
            'is_verified' => 1,
            'organization_id' => Auth::user()->organization_id,
            'password'=> Hash::make(Str::random(8))
            ]);

        return $user;
 }

    public function createMember(  $request){
           $member= Member::create( [
            'user_id' => $this->createUser($request)->id,
            'team_id' => $request->post('team_id' ),
        ] );
        return [
            'data' => new MemberResource($member),
            'status' => true,
            'identifier_code' => 110001,
            'status_code' => 200,
            'message' => 'member created successfully'
        ];


    }


    public function updateMember(  $id ,  $request){
        $member=Member::find($id);
        if(!$member){
            return [
                'data' => null,
                'status' => true,
                'identifier_code' => 110003,
                'status_code' => 200,
                'message' => 'this member not exist '
            ];

        }
        $user = $member->user()->first();
        $email = $user->email;
        $user->update([
            'first_name' => $request->post('first_name'),
            'last_name' => $request->post('last_name'),
            'email' => $request->post('email'),
            'role' => $request->post('role'),
            ]);
            $member->update([
                'team_id'=>$request->post('team_id'),
                ]);

            if($email != $request->post('email')){
                $user->update(['is_verified' => 0]);
                dispatch(new SendAdminVerificarionMailJob($user->fresh(),new UserRepository()));
            }

                return [
                    'data' => new UpdateMemberResource($user),
                    'status' => true,
                    'identifier_code' => 110002,
                    'status_code' => 200,
                    'message' => 'member updated successfully'
                ];


            }

        public function deleteMember($id)
            {
                $member=Member::find($id);
                $user = $member->user()->first();
                return $user->delete();
            }


        public function updateMemberStatus(  $request){
            $member=Member::find($request->post('member_id')) ;
            if(!$member){
                return [
                    'data' => null,
                    'status' => true,
                    'identifier_code' => 111002,
                    'status_code' => 200,
                    'message' => 'this member not exist '
                ];

            }
            $user = $member->user()->first();
            $user->update([
                'status' => $request->post('status') == 'true' ? 1 : 0,

                ]);

                    return [
                        'data' => new ListMembersResource($user),
                        'status' => true,
                        'identifier_code' => 111001,
                        'status_code' => 200,
                        'message' => 'member status updated successfully'
                    ];
                }



}



?>
