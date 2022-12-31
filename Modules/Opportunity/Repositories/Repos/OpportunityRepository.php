<?php

namespace Modules\Opportunity\Repositories\Repos;

use Auth;
use Exception;
use Modules\Opportunity\Http\Resources\OpportunityResource;
use Modules\Opportunity\Http\Resources\OpportunityCreationResource;
use Modules\Opportunity\Http\Resources\OpportunityPreviewResource;
use Modules\Client\Models\Client;
use Modules\Client\Models\ClientMrrService;
use Modules\Client\Models\ClientOrrService;
use Modules\Client\Models\ClientQuestion;
use Modules\Opportunity\Models\Opportunity;
use Modules\Opportunity\Repositories\Interfaces\IOpportunityRepository;
use Modules\Question\Repositories\Repos\QuestionRepository;
use Modules\Question\Models\Question;
use Modules\Services\Models\Service;

class OpportunityRepository implements IOpportunityRepository
{


    // list all opportunities
    public function listOPPortunities()
    {

        $qb = Client::when(request()->has('client_id'),function($q){
            $q->where('id',request('client_id'));
        })->whereOrganizationId(Auth::user()->organization_id)
        ->Has("opportunities")
        ->With('opportunities')
        ->WithCount('opportunities')

        ->when(request()->has('search'),function($q){
                $q->where(function($qq){
                    $qq->where('contact_person','LIKE', '%'.request('search').'%')
                    ->orWhere('email','LIKE','%'.request('search').'%');
                });
            })

        ->when(request()->has('status'),function($q){
                $q->where('status',request('status'));
            })

        ->when(request()->has('sort'), function($q){
            if(request('sort') == 'a-z'){
                $q->orderBy('contact_person','ASC');
            } elseif(request('sort') == 'z-a') {
                $q->orderBy('contact_person','DESC');
            }elseif(request('sort') == 'higher') {
                $q->orderBy('questions_count','DESC');
            }elseif(request('sort') == 'lower') {
                $q->orderBy('questions_count','ASC');
            }
        });

        $opportunity = isset(request()->paginate) && request()->paginate == "false" ?  $qb->get() : $qb->paginate(config('app.per_page')) ;
        $message = count($opportunity) > 0 ? "opportunity listed successfully" : "No opportunities exist";
        return [
            'data' => OpportunityResource::collection($opportunity)->response()->getData(true),
            'status' => true,
            'identifier_code' =>153001,
            'status_code' => 200,
            'message' => $message
        ];
    }


    // create new opportunity
    public function createOPPortunities($request)
    {
        // check if client exist or not
        $client = Client::where("id",$request->post('client_id'))
                        ->Where("organization_id",Auth::user()->organization_id)->first();

        if(!is_null($client)){

            // check if service exist or not
            $service = Service::Where("id",$request->post('service_id'))
                              ->Where("organization_id",Auth::user()->organization_id)
                              ->first();
            if(!is_null($service)){
                // check if opportunity exist before or not
                $checkOpportunityExist = Opportunity::Where("service_id",$request->post('service_id'))
                                                    ->Where("client_id",$request->post('client_id'))
                                                    ->Where("organization_id",Auth::user()->organization_id)
                                                    ->first();
                if(is_null($checkOpportunityExist))
                {
                    if(ClientMrrService::where('service_id',$request->post('service_id'))->where('client_id',$request->post('client_id'))->exists()
                     || ClientOrrService::where('service_id',$request->post('service_id'))->where('client_id',$request->post('client_id'))->exists()){

                        return [
                            'data'            => NULL,
                            'status'          => false,
                            'identifier_code' => 156005,
                            'status_code'     => 400,
                            'message'         => 'This Service Already Exist with this Client'
                        ];
                     }
                    // create new opportunity
                    $result= Opportunity::create( [
                        'service_id'        => $request->post('service_id'),
                        'notes'             => $request->post('opportunity_notes'),
                        'client_id'         => $request->post('client_id'),
                        'organization_id'   => Auth::user()->organization_id,
                    ]);
                    return [
                        'data'             => new  OpportunityCreationResource($result),
                        'status'           => true,
                        'identifier_code'  => 156001,
                        'status_code'      => 200,
                        'message'          => 'Opportunity created successfully'
                    ];
                }
                else
                {
                    return [
                        'data'            => NULL,
                        'status'          => false,
                        'identifier_code' => 156002,
                        'status_code'     => 400,
                        'message'         => 'Opportunity already exist'
                    ];
                }

            }else{
                return [
                    'data'            => NULL,
                    'status'          => false,
                    'identifier_code' => 156003,
                    'status_code'     => 400,
                    'message'         => 'Service is not exist'
                ];
            }

        }else{
            return [
                'data'            => NULL,
                'status'          => false,
                'identifier_code' => 156004,
                'status_code'     => 400,
                'message'         => 'Client is not exist'
            ];
        }


    }



    public function previewOpportunities($id)
    {

        $data = ClientQuestion::whereId($id)
        ->with('question',function($q){
            $q->where('type','service');
        })->where('is_opportunity',1);
        $opportunity = $data->first();
        if(!is_null($opportunity))
        {
            return [
                'data' => OpportunityPreviewResource::collection($opportunity),
                'status' => true,
                'identifier_code' =>164001,
                'status_code' => 200,
                'message' => 'opportunity previwed successfully'
            ];
        }
        else
        {
            return [
                'data' => null,
                'status' => false,
                'identifier_code' =>164002,
                'status_code' => 400,
                'message' => 'opportunity is not exist'
            ];
        }


    }

    // preview opportunities related to specifi client
    public function previewClientOpportunities($id)
    {

        // get all client opportunities
        $data= Opportunity::where("client_id",$id)
                            ->Where("organization_id",Auth::user()->organization_id)
                            ->when(request()->has("service_type"),function($q){
                                $q->whereHas("service",function($qq){
                                    $qq->where("type",request()->service_type);
                                });
                            })
                            ->orderBy('id','DESC');


        // paginate response or not depending on reques
        $opportunity = isset(request()->paginate) && request()->paginate == "false" ?  $data->get() : $data->paginate(config('app.per_page')) ;
        return [
            'data' => OpportunityPreviewResource::collection($opportunity)->response()->getData(true),
            'status' => true,
            'identifier_code' =>164001,
            'status_code' => 200,
            'message' => 'opportunity previwed successfully'
        ];

    }

   // delete opportunity
    public function deleteOpportunity($id)
    {

        // check opportunity existence
        $opportunity = Opportunity::where("id", $id)
                        ->Where("organization_id",Auth::user()->organization_id)
                        ->first();
        // delete opporunity
        if(!is_null($opportunity))
        {
            $opportunity->delete();
            return [
                'data' => null,
                'status' => true,
                'identifier_code' =>157001,
                'status_code' => 200,
                'message' => 'opportunity deleted successfully'
            ];
        }
        // return error message
        else
        {
            return [
                'data' => null,
                'status' => false,
                'identifier_code' =>157002,
                'status_code' => 400,
                'message' => 'opportunity is not exist'
            ];
        }
    }

    // update opportunity
    public function updateOpportunity($id , $request){
        // check opportunity existence
        $opp = Opportunity::where("id", $id)
                        ->Where("client_id",$request->client_id)
                        ->Where("organization_id",Auth::user()->organization_id)
                        ->first();

        if(!is_null($opp))
        {
            $checkOpportunityExist = Opportunity::where(function($q) use($id, $request) {
                $q->where("client_id",$request->client_id);
                $q->where('service_id',$request->post('service_id'));
                $q->where("organization_id",Auth::user()->organization_id);
                $q->whereNotIn('id',[$id]);
            })->exists();

            if($checkOpportunityExist){
                return [
                    'data' => null,
                    'status' => false,
                    'identifier_code' =>157003,
                    'status_code' => 400,
                    'message' => 'Opportunity already exist'
                ];
            }
            $opp->update([
                'notes' => $request->post('opportunity_notes'),
                'service_id' => $request->post('service_id'),
                ]);

                return [
                    'data' => new  OpportunityPreviewResource($opp->fresh()),
                    'status' => true,
                    'identifier_code' =>157001,
                    'status_code' => 200,
                    'message' => 'opportunity updated successfully'
                ];

        }
        else
        {
            return [
                'data' => null,
                'status' => false,
                'identifier_code' =>157002,
                'status_code' => 400,
                'message' => 'opportunity is not exist'
            ];
        }

 }


}

?>
