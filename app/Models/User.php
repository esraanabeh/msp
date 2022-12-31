<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Modules\Members\Models\Member;
use Modules\Team\Models\Team;
use Laravel\Sanctum\HasApiTokens;
use  Modules\Organization\Models\OrganizationAdmin;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Cashier\Billable;
use Modules\SubscriptionPlan\Models\Subscription;
use Spatie\Activitylog\Traits\LogsActivity;
use Modules\Authentication\Notifications\SendForgetPasswordCodeMail;
use Modules\Authentication\Notifications\NewUserAdded;
use Illuminate\Support\Facades\Route;
use Thomasjohnkane\Snooze\Traits\SnoozeNotifiable;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, InteractsWithMedia, Notifiable , Billable ,LogsActivity, SnoozeNotifiable;

    protected static $logAttributes =['first_name','last_name','email','status'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'email_verified_at',
        'is_verified',
        'allow_2fa',
        'status',
        'phone_number',
        'google2fa_secret',
        'role',
        'organization_id',
        'temp_password'
    ];

    public function getImageAttribute($value)
    {
        if($value) return url($value);

        return $value;
    }

    /**
     * @return string
     */
    public function getCoverAttribute(): string
    {
        return $this->getFirstMediaUrl('avatar')  ;
    }


    public function getFirstMediaUrl(string $collectionName = 'default', string $conversionName = ''): string
    {
        $media = $this->getFirstMedia($collectionName);

        if (!$media) {
            return $this->getFallbackMediaUrl($collectionName) ?: asset('avatar/no.png');
        }

        if ($conversionName !== '' && !$media->hasGeneratedConversion($conversionName)) {
            return $media->getUrl();
        }

        return $media->getUrl($conversionName);
    }


/**
     * @var string[]
     */
    protected $appends= [
        'cover',
        'organization_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret'

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getOrganization()
    {
        if($this->organization_admin()->exists()){
            return $this->organization_admin->Organization;
        } elseif($this->member()->exists()) {
            return $this->member->team->organization;
        }else {
            return null;
        }
    }

    public function getOrganizationAttribute()
    {
        if($this->organization_admin()->exists()){
            return $this->organization_admin->Organization;
        } elseif($this->member()->exists()) {
            return $this->member->team->organization;
        }else {
            return null;
        }
    }

    public function getFullNameAttribute(){
        return $this->first_name." ".$this->last_name;
    }

    public function verify()
    {
        return $this->hasOne(Verification::class, 'user_id');
    }


    public function member()
    {
        return $this->hasOne(Member::class, 'user_id');
    }


    public function organization_admin()
    {
     return $this->hasOne(OrganizationAdmin::class, 'user_id');
    }


    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'user_id');
    }

    public function sendPasswordResetNotification($token)
    {
        $url = 'https://example.com/reset-password?token='.$token;
        if(Route::currentRouteName() == 'user.forget-password'){
            $url = config('app.front_url').'/auth/reset-password?token='.$token.'&email='.$this->email;
            $this->notify(new SendForgetPasswordCodeMail($url));
        } elseif (Route::currentRouteName() == 'staff.member.create') {
            $url = config('app.front_url').'/auth/organization/signUP?token='.$token.'&email='.$this->email.'&name='.$this->first_name.'&organization_name='.$this->member->team->organization->name;
            $this->notify(new NewUserAdded($url));
        }

    }

    public function getOrganizationIdAttribute()
    {
        if($this->organization_admin()->exists()){
            return $this->organization_admin->Organization->id;
        } elseif($this->member()->exists()) {
            return $this->member->team->organization->id;
        }else {
            return null;
        }
    }
    public function notifications()
    {
        return $this->morphMany(CustomNotification::class, 'notifiable');
    }



}
