<?php

namespace Modules\Organization\Repositories;
use Modules\Organization\Models\Organization;
use Modules\Notifications\Models\OrganizationNotification;
use Modules\Notifications\Models\Notification;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Image\Manipulations;
use Modules\Organization\Http\Resources\OrganizationResource;
use Modules\Organization\Http\Resources\MediaResource;
use Modules\Organization\Http\Resources\OrganizationInformationResource;

use Modules\Organization\Http\Resources\TermsResource;
use Modules\SubscriptionPlan\Models\Subscription;
use Exception;

class OrganizationRepository {

    public function createOrganization($request){
           return Organization::create( [
            'name' => $request->post('name'),
            'team_members' => $request->post('team_members'),
            // 'usage_system_target' => $request->post('usage_system_target'),
            'main_speciality' => $request->post('main_speciality'),

        ] );


    }

    public function updateProfile(  $request ){
        $data= Organization::find(Auth::user()->organization_id);
        $data->update([
            'name'=> $request->name,
            'phone_number'=> $request->phone_number,
            'email'=> $request->email,
            'website_url'=> $request->website_url,
        ]);
        return $data;

    }

    public function getOrgInfo()
    {
        $OrgInfo = Organization::find(Auth::user()->organization_id);
        return [
            'data' => new OrganizationResource($OrgInfo),
            'status' => true,
            'identifier_code' => 136001,
            'status_code' => 200,
            'message' => 'Organization Information listed successfully'

    ];
    }



    public function uploadLogo(  $request ){
        $data= Organization::find(Auth::user()->organization_id);
        if ($request->hasFile('logo')) {
            $data->clearMediaCollection('logo');
            $data->addMediaFromRequest('logo')->toMediaCollection('logo');
        }
        return [
            'data' => new OrganizationResource($data),
            'status' => true,
            'identifier_code' => 133001,
            'status_code' => 200,
            'message' => 'Logo Uploaded successfully'
        ];

    }

    public function updateOrgLogo(  $request ){
        $data= Organization::find(Auth::user()->organization_id);
        if ($request->hasFile('logo')) {
            $data->clearMediaCollection('logo');
            $data->addMediaFromRequest('logo')->toMediaCollection('logo');
        }
        return [
            'data' => new OrganizationResource($data),
            'status' => true,
            'identifier_code' => 134001,
            'status_code' => 200,
            'message' => 'Logo updated successfully'
        ];

    }


    public function getOrgLogo()
    {
        $userId = Auth::user()->id;
        $mediaItems = Organization::find(Auth::user()->organization_id);
        if(Auth::user()->organization_admin()->exists()){
            $userId = Auth::user()->id;
        } else {
            try{
                $userId = Auth::user()->organization->organization_admin()->first()->user_id;
            }catch(Exception $e){
                Log::error($e->getMessage());
            }
        }

        $subscription = Subscription::where("user_id",$userId)
                                    ->where(function ($q){
                                        $q->Where("stripe_status","trialing")
                                        ->orWhere("stripe_status","active");
                                    })
                                    ->WhereNull("ends_at")
                                    ->orderBy("id","DESC")
                                    ->first();
                                    // dd($subscription);
        $mediaItems = Organization::find(Auth::user()->organization_id);
        $mediaItems['plan'] = $subscription;
        return [
            'data' => new MediaResource($mediaItems),
            'status' => true,
            'identifier_code' => 135001,
            'status_code' => 200,
            'message' => 'Logo listed successfully'
    ];
    }


    public function updateTerms(  $request ){
        $data= Organization::find(Auth::user()->organization_id);
        $data->update([
            'terms_and_conditions'=> $request->terms_and_conditions,
        ]);

        return [
            'data' => new OrganizationResource($data),
            'status' => true,
            'identifier_code' => 137001,
            'status_code' => 200,
            'message' => 'Terms and Conditions updated successfully'
        ];
    }

    public function getTerms()
    {
        $terms = Organization::find(Auth::user()->organization_id);
        return [
            'data' => new TermsResource($terms),
            'status' => true,
            'identifier_code' => 138001,
            'status_code' => 200,
            'message' => 'terms and conditions listed successfully'
    ];
    }

}



?>
