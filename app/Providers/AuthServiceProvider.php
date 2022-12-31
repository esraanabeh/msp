<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Route;
use Modules\Organization\Models\Organization;
use Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function ($user, string $token) {
            if(Route::currentRouteName() == 'user.forget-password'){
                return config('app.front_url').'/auth/reset-password?token='.$token.'&email='.$user->email;
            } elseif (Route::currentRouteName() == 'staff.member.create') {
                return config('app.front_url').'/auth/organization/signUP?token='.$token.'&email='.$user->email.'&name='.$user->first_name.'&organization_name='.$user->member->team->organization->name;
            }
        });
    }
}
