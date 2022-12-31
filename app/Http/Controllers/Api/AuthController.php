<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Verification;
use App\Mail\SendCodeUser;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUser;
use App\Http\Requests\LoginUser;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    use ApiResponseTrait;
    /**
     * @var User
     */
    protected $userModel;
    protected $user;

    /**
     * @param User $user
     */
    public function __construct(IUserRepository $user)
    {
        $this->user = $user;
    }

    /**
     * @param Request $request
     * @return Response
     */






    public function verify_code(Request $request){

        $data = User::whereEmail($request->post('email'))->first();
        $verify_user = Verification::whereCode($request->post('code'))->first();

        if($verify_user->is_used==1){
            return $this->apiResponse( 'this is email vriefied before','false','100004',200,'error' );
        }

        $verify= $this->user->verify($request->post('email'),$request->post('code'));
        if($verify){
                return $this->apiResponse( new UserResource($data),'true','100001',200,'sucessfuly' );
            }
        else{
            return $this->apiResponse( 'incorrect code','false','100002',400,'error' );
        }

    }










}
