<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponseTrait;

class Workspace
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
        if ( Auth::user()->role == "admin" ) {
            if(Auth::user()->organization_admin){
                return $this->apiResponse(NULL ,'false','127001',400,"You donn't have the permission to execute that process, You are not be allowed to has more than one organization workspace");
            }
            return $next( $request );
        }

        return $this->apiResponse(NULL ,'false','127001',400,"You donn't have the permission to execute that process");

    }
}
