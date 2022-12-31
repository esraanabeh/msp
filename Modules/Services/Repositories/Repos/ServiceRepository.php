<?php

namespace Modules\Services\Repositories\Repos;
use Modules\Services\Models\Service;
use Carbon\Carbon;
use Modules\Services\Repositories\Interfaces\IServiceRepository;
use Illuminate\Support\Facades\Hash;
use Modules\Organization\Models\Organization;
use Modules\Services\Models\MasterServiceAgreement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Services\Http\Resources\MasterAgreementResource;
use Modules\Services\Http\Resources\ServiceResource;

class ServiceRepository implements IServiceRepository{



public function getServices()
{
    $services =  Service::whereHas('organization',function($query){
        $query->whereHas('organization_admin',function($query){
            $query->whereuserId(auth()->user()->id);
        });
    })->orderBy("id","desc")->paginate(config('app.per_page'));

    return [
        'data' => ServiceResource::collection($services)->response()->getData(true),
        'status' => true,
        'identifier_code' => 120001,
        'status_code' => 200,
        'message' => 'List of services'
    ];
}


public function createService($request){
    $service = Service::where("title",$request->title)->where("organization_id",Auth::user()->organization_id)->first();
    if(is_null($service)){
        try
        {
            $service = Service::create( [
                'title' => $request->post( 'title' ),
                'unit_cost' => $request->post( 'unit_cost' ),
                'type' => $request->post( 'type' ),
                'organization_id' =>Auth::user()->organization_id,
                ] );
                return [
                'data' => new ServiceResource($service),
                'status' => true,
                'identifier_code' => 112001,
                'status_code' => 200,
                'message' => 'Service created successfully'
            ];
        }
        catch(\Exception $ex)
        {
            Log::info("Create new service process failed due to : ".$ex->getMessage());
            return [
                'data' => NULL,
                'status' => false,
                'identifier_code' => 112002,
                'status_code' => 400,
                'message' => 'Some thing went wrong, plz try again later'
            ];
        }

    }else{
        return [
            'data' => NULL,
            'status' => false,
            'identifier_code' => 112003,
            'status_code' => 400,
            'message' => 'Service is already exist'
        ];
    }

}

public function createMasterAgreement( $request){
    try
    {
       $masterServiceAgreement = MasterServiceAgreement::all();
       $isDefault = count($masterServiceAgreement)>0 ? ($request->post( 'isDefault' ) ? 1:0) : 1;
        if(count($masterServiceAgreement)>0 && $request->post( 'isDefault' )){
            MasterServiceAgreement::query()->update(['isDefault'=>0]);
        }

        $msa = MasterServiceAgreement::create( [
            'title' => $request->post( 'title' ),
            'isDefault' => $isDefault,
            'organization_id' => Auth::user()->organization_id,
        ] );

    if($request->hasFile('file') && $request->file('file')->isValid()) {
        $msa->addMediaFromRequest('file')->toMediaCollection('file');
    }
        return [
            'data' => new MasterAgreementResource($msa),
            'status' => true,
            'identifier_code' => 146001,
            'status_code' => 200,
            'message' => 'Master service agreement created successfully'
        ];
    }
    catch(\Exception $ex)
    {
        Log::info("Create new master service agreement process failed due to : ".$ex->getMessage());
        return [
            'data' => NULL,
            'status' => false,
            'identifier_code' => 146002,
            'status_code' => 400,
            'message' => 'Some thing went wrong, plz try again later'
        ];
    }
}

public function updateMasterAgreementDefault( $request){
    try
    {
        $msa = MasterServiceAgreement::find($request->id);
        if(!is_null($msa)){
            $msa->update(['isDefault' => 1]);
            MasterServiceAgreement::query()->whereNotIn('id',[$msa->id])->update(['isDefault'=>0]);

            return [
                'data' => new MasterAgreementResource($msa),
                'status' => true,
                'identifier_code' => 148001,
                'status_code' => 200,
                'message' => 'Master service agreement updates successfully'
            ];
        }
        else
        {
            return [
                'data' => NULL,
                'status' => false,
                'identifier_code' => 148002,
                'status_code' => 400,
                'message' => 'Master Service agreement is not exist'
            ];
        }
    }
    catch(\Exception $ex)
    {
        Log::info("Update master service agreement process failed due to : ".$ex->getMessage());
        return [
            'data' => NULL,
            'status' => false,
            'identifier_code' => 148003,
            'status_code' => 400,
            'message' => 'Some thing went wrong, plz try again later'
        ];
    }
}


public function getServicesAgreements()
{
    $msa = MasterServiceAgreement::whereHas('organization',function($query){
        $query->whereHas('organization_admin',function($query){
            $query->whereuserId(auth()->user()->id);
        });
    })->orderBy("id","desc")->paginate(config('app.per_page'));

    return [
    'data' => MasterAgreementResource::collection($msa)->response()->getData(true),
    'status' => true,
    'identifier_code' => 149001,
    'status_code' => 200,
    'message' => 'List of master services agreements'
    ];
}


public function deleteServiceAgreement($id)
{
    try{
        $service=MasterServiceAgreement::find($id);
        if(!is_null($service))
        {
            if($service->isDefault)
            {
                return [
                    'data' => new MasterAgreementResource($service),
                    'status' => false,
                    'identifier_code' => 147004,
                    'status_code' => 400,
                    'message' => 'The Master Service Agreement is default, Default one can not be deleted'
                ];
            }
            else
            {
                $service->delete();
                return [
                    'data' => new MasterAgreementResource($service),
                    'status' => true,
                    'identifier_code' => 147001,
                    'status_code' => 200,
                    'message' => 'Master Service Agreement deleted successfully'
                ];
            }

        }
        else
        {
            return [
                'data' => NULL,
                'status' => false,
                'identifier_code' => 147002,
                'status_code' => 400,
                'message' => 'The Master Service Agreement is not exist'
            ];
        }

    }
    catch(\Exception $ex)
    {
        Log::info("Delete master service agreement process failed due to : ".$ex->getMessage());
        return [
            'data' => NULL,
            'status' => false,
            'identifier_code' => 147003,
            'status_code' => 400,
            'message' => 'Some thing went wrong, plz try again later'
        ];
    }

}





    public function updateService($request){
        $org=auth()->user()->organization_admin;
        if ($org && $org->organization_id === (int) Auth::user()->organization_id)
        {

            $serv=[];
            foreach($request->services as $services ){
            $data= Service::updateOrCreate(
                    ['id' => $services['id'] ?? null] ,
                    [  'title' => $services['title'],
                        'unit_cost' => $services['unit_cost'],
                        'type' => $services['type'],
                        'organization_id' => Auth::user()->organization_id
                    ]
                );
                $serv[]=$data;
            }

            $services = Service::where('organization_id',Auth::user()->organization_id)->paginate(config('app.per_page'));
            return [
                        'data' =>  ServiceResource::collection($services)->response()->getData(true),
                        'status' => true,
                        'identifier_code' => 119001,
                        'status_code' => 200,
                        'message' => 'Service updated successfully'
                    ];
        }
        else
        {
            return [
                'data' =>  'error',
                'status' => false,
                'identifier_code' => 119002,
                'status_code' => 200,
                'message' => 'this user is not oranization admin'
            ];
        }

    }

public function deleteService($id)
{
    try{
    $service=Service::find($id);
    if(!is_null($service))
    {
        $update_service=$service->delete();
        $services = Service::where('organization_id',Auth::user()->organization_id)->paginate(config('app.per_page'));
            return [
                'data' => ServiceResource::collection($services)->response()->getData(),
                'status' => true,
                'identifier_code' => 145001,
                'status_code' => 200,
                'message' => 'Service deleted successfully'
            ];
    }
    else
    {
        return [
            'data' => NULL,
            'status' => false,
            'identifier_code' => 145002,
            'status_code' => 400,
            'message' => 'The Service is not exist'
        ];
    }

}
catch(\Exception $ex)
{
    Log::info("Delete service process failed due to : ".$ex->getMessage());
    return [
        'data' => NULL,
        'status' => false,
        'identifier_code' => 145003,
        'status_code' => 400,
        'message' => 'Some thing went wrong, plz try again later'
    ];
}

}

public function listMRRServices()
    {
        $MRR_service = Service::where('organization_id',Auth::user()->organization_id)->where('type','MRR')
        ->when(request()->has('search'),function($q){
            $q->where('title','LIKE', '%'.request('search').'%');
        });

        return [
            'data' => ServiceResource::collection($MRR_service->paginate(config('app.per_page')))->response()->getData(true),
            'status' => true,
            'identifier_code' => 169001,
            'status_code' => 200,
            'message' => 'Services'
        ];
    }

    public function listORRServices()
    {
        $ORR_service = Service::where('organization_id',Auth::user()->organization_id)->where('type','ORR')
        ->when(request()->has('search'),function($q){
            $q->where('title','LIKE', '%'.request('search').'%');
        });

        return [
            'data' => ServiceResource::collection($ORR_service->paginate(config('app.per_page')))->response()->getData(true),
            'status' => true,
            'identifier_code' => 170001,
            'status_code' => 200,
            'message' => 'ORR questions'
        ];
    }




}



?>
