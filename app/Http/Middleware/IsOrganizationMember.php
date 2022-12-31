<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponseTrait;
use Modules\Authentication\Http\Resources\UserResource;
use Modules\Organization\Models\OrganizationAdmin;
use Modules\SubscriptionPlan\Models\Subscription;

class IsOrganizationMember
{

    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user())
        {
            // check workspace existence
            if(is_null(Auth::user()->organization_id)){

                $data = [
                    'user'         => new UserResource(auth()->user()),
                    'access_token' => $request->bearerToken()
                ];
                return $this->apiResponse($data ,'false','101007',400,"You donn't have workspace, please setup your workspace");
            }
            else
            {
                // check subscription
                $userID = Auth::user()->id;
                if(!Auth::user()->organization_admin){
                    $userID = OrganizationAdmin::where("organization_id",Auth::user()->organization_id)->first()->user_id;
                }
                $subscription = Subscription::where("user_id",$userID)
                                        ->orderBy("id","DESC")
                                        ->first();

                // no active subscription
                if(is_null($subscription) || !in_array($subscription->stripe_status ,["trialing","active"])  || !is_null ($subscription->ends_at))
                {
                    $data = [
                        'user' => new UserResource(auth()->user()),
                        'access_token' => $request->bearerToken()
                    ];
                    $msg = Auth::user()->organization_admin ? 'You have no active subscription plan, please subscribe to be able to access system features' : 'Your organization has no active subscription plan, please contact your organization admin';
                    return $this->apiResponse($data ,'true','101004',200,$msg);
                }
            }

            return $next( $request );
        }

        return $this->apiResponse(NULL ,'false','127001',400,"You donn't have the permission to execute that process");

    }
}
