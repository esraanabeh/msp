<?php

namespace Modules\Client\Repositories\Repos;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Modules\Client\Events\AssignClientTemplate;
use Modules\Client\Http\Resources\ClientResource;
use Modules\Client\Http\Resources\ClientQuestionResource;
use Modules\Client\Http\Resources\ClientSectionResource;
use Modules\Client\Http\Resources\ClientTaskResource;
use Modules\Client\Http\Resources\ClientTemplateResource;
use Modules\Client\Models\Client;
use Modules\Templates\Models\TemplateClient;
use Modules\Services\Models\Service;
use Modules\Templates\Models\Template;
use Modules\Client\Models\ClientQuestion;
use Modules\Client\Repositories\Interfaces\IClientRepository;
use Modules\Opportunity\Http\Resources\OpportunityResource;
use Modules\Opportunity\Models\Opportunity;
use Modules\Question\Repositories\Repos\QuestionRepository;
use Modules\Quote\Http\Resources\ClientOpportunityResource;
use Modules\Quote\Http\Resources\ClientServiceResource;
use Modules\Quote\Models\ClientQuote;
use Modules\Tasks\Models\ClientTask;
use Modules\Templates\Models\ClientSection;
use Modules\Templates\Models\Section;
use Modules\Client\Http\Resources\ClientMRRServiceResource as QuoteMRRServiceResource;
use Modules\Client\Http\Resources\ClientORRServiceResource as QuoteORRServiceResource;



class ClientRepository implements IClientRepository
{
    protected $questionRepository;

    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    public function createClient($data)
    {
        $user = Auth::user();

        try{
            $client = new Client();
            $client->company_name = $data['company_name'];
            $client->contact_person = $data['contact_person'];
            $client->phone_number = $data['phone_number'];
            $client->email = $data['email'];
            $client->address = $data['address'] ?? null;
            $client->industry = $data['industry'];
            $client->additional_questions = $data['additional_questions'] ?? null;
            $client->number_of_employees = $data['number_of_employees'] ?? null;
            $client->organization_id = $user->organization_id;

            $client->save();
            return [
                'data' => [
                    'client' => new ClientResource($client),
                    ],
                'status'          => true,
                'identifier_code' => 125001,
                'status_code'     => 200,
                'message'         => 'client created successfully'
            ];
        } catch(\Exception $e){
            return [
                'data'            => null,
                'status'          => false,
                'identifier_code' => 102002,
                'status_code'     => 400,
                'message'         => 'Some thing went wrong, please try again later'
            ];
        }
    }

    public function listClients()
    {
        $qb = Client::whereOrganizationId(Auth::user()->organization_id)
            ->when(request()->has('search')&& !is_null(request('search')) && request('search') != "",function($q){
                    $q->where(function($z){
                        $z->where('contact_person','LIKE', '%'.request('search').'%')
                            ->orWhere('email','LIKE','%'.request('search').'%');
                    });
                })

            ->when(request()->has('status')&& !is_null(request('status')) && request('status') != "",function($q){
                    $q->where('status',request('status'));
                })

            ->when(request()->has('sort')&& !is_null(request('sort')) && request('sort') != "", function($q){
                if(request('sort') == 'client_name'){
                    $q->orderBy('contact_person','ASC');
                } elseif(request('sort') == 'company') {
                    $q->orderBy('company_name','ASC');
                }
            });

            $qb->whereOrganizationId(Auth::user()->organization_id)->orderBy('id','DESC');

        $clients = $qb->paginate(config('app.per_page'));

        return [
            'data' => ClientResource::collection($clients)->response()->getData(true),
            'status' => true,
            'identifier_code' => 132001,
            'status_code' => 200,
            'message' => 'clients'
        ];
    }



    public function handleClientQuestions($clientId,$data)
    {
        $client=Client::find($clientId);
        if ($client){
            try {
                    $question=ClientQuestion::create([
                        'question' => $data['question'],
                            'service_id' => $data['service_id'],
                            'client_id' => $clientId,
                            'organization_id' => Auth::user()->organization_id,
                        ]);

                    return [
                        'data'            => new ClientQuestionResource ($question),
                        'status'          => true,
                        'identifier_code' => 144001,
                        'status_code'     => 200,
                        'message'         => "Question Added Successfully"
                    ];
                }
            catch(\Exception $e){
                return [
                    'data'            => null,
                    'status'          => false,
                    'identifier_code' => 144002,
                    'status_code'     => 400,
                    'message'         => 'Some thing went wrong, please try again later'
                ];
            }
        }
        else {
            return [
                'data' =>null,
                'status' => false,
                'identifier_code' => 144003,
                'status_code' => 400,
                'message' => 'Client does not exist'
            ];

        }

    }

    // update client quote question
    public function updateClientQuestions($clientId,$request)
    {

        $client=Client::where('id',$clientId)->where('organization_id',Auth::user()->organization_id)->first();

        if ($client){
            $ques=[];
            try {
                // begin database transaction
                DB::beginTransaction();
                foreach($request->questions as $question ){

                    $data=ClientQuestion::updateOrCreate(
                        ['id'             => $question['id'] ?? null] ,
                        ['question'       => $question['question'],
                        'service_id'      => $question['service_id'],
                        'organization_id' => Auth::user()->organization_id,
                        'client_id'       => $clientId
                        ]);
                        $ques[]=$data;
                }
                $all_ques = ClientQuestion::where('organization_id',Auth::user()->organization_id)->where('client_id',$clientId)->orderBy('id','DESC');
                $client_questions = isset(request()->paginate) && request()->paginate == "false" ?  $all_ques->get() : $all_ques->paginate(config('app.per_page')) ;

                // Happy ending :)
                DB::commit();
                return [
                    'data'            =>  ClientQuestionResource ::collection($client_questions)->response()->getData(true),
                    'status'          => true,
                    'identifier_code' => 210001,
                    'status_code'     => 200,
                    'message'         => "Question Updated Successfully"
                ];
            }
            catch(\Exception $e)
            {
                // rollback!!!
                DB::rollback();
                Log::info("update or create client issue : ".$e->getMessage());
                return [
                    'data'            => null,
                    'status'          => false,
                    'identifier_code' => 210002,
                    'status_code'     => 400,
                    'message'         => 'Some thing went wrong, please try again later'
                ];
            }
        }


        else {
            return [
                'data' =>null,
                'status' => false,
                'identifier_code' => 210003,
                'status_code' => 400,
                'message' => 'this is not organization admin'
            ];

        }

    }


    // Delete client quote question
    public function deleteClientQuestions($questionId)
    {
        $question=ClientQuestion::where('id',$questionId)->whereHas('client',function($q){
            $q->where('organization_id',Auth::user()->organization_id);
        })->first();

        if ($question){
            try {
                    $question->delete();
                    return [
                        'data'            => new ClientQuestionResource ($question),
                        'status'          => true,
                        'identifier_code' => 210001,
                        'status_code'     => 200,
                        'message'         => "Question Deleted Successfully"
                    ];
                }
            catch(\Exception $e){
                return [
                    'data'            => null,
                    'status'          => false,
                    'identifier_code' => 210002,
                    'status_code'     => 400,
                    'message'         => 'Some thing went wrong, please try again later'
                ];
            }
        }
        else {
            return [
                'data' =>null,
                'status' => false,
                'identifier_code' => 210003,
                'status_code' => 400,
                'message' => 'Question does not exist'
            ];

        }

    }

    public function listClientQuestions($clientId)
    {
        $client=Client::find($clientId);
        if ($client){
            $data=ClientQuestion::where('client_id',$clientId);

            $questions = isset(request()->paginate) && request()->paginate == "false" ?  $data->get() : $data->paginate(config('app.per_page')) ;
        return [
            'data' =>ClientQuestionResource::collection( $questions)->response()->getData(true),
            'status' => true,
            'identifier_code' => 202001,
            'status_code' => 200,
            'message' => 'Client Questions listed successfully'
        ];
        }
        else {
            return [
                'data' =>null,
                'status' => false,
                'identifier_code' => 202002,
                'status_code' => 400,
                'message' => 'Client does not exist'
            ];

        }

    }



    public function changeClientStatus($data,$clientId)
    {
        $client = Client::find($clientId);
        if($client){
            $client->update([
                'status' => $data['status']
            ]);

            if($data['status'] == 'Active'){
                $quote = ClientQuote::where('client_id',$clientId)->where('is_sent',1)->latest()->first();
                if($quote){
                    $quote->update([
                        'status' => 'accepted'
                    ]);
                }
            }

            return [
                'data' =>new ClientResource($client->refresh()),
                'status' => true,
                'identifier_code' => 188001,
                'status_code' => 200,
                'message' => "client status updated successfully"
            ];
        }else {
            return [
                'data' =>null,
                'status' => false,
                'identifier_code' => 188002,
                'status_code' => 400,
                'message' => "client not found"
            ];
        }
    }
    public function deleteClient($clientId)
    {
        $client = Client::whereId($clientId)->where('organization_id',Auth::user()->organization_id)->first();
        if($client){
            $client->delete();
            return [
                'data' => new ClientResource($client),
                'status' => true,
                'identifier_code' => 189001,
                'status_code' => 200,
                "message" => "client deleted successfully"
            ];
        } else {
            return [
                'data' => null,
                'status' => false,
                'identifier_code' => 189002,
                'status_code' => 400,
                "message" => "client not found"
            ];
        }
    }
    public function assignClientTemplate($data,$clientId )
    {

        // check client existence
        $client=Client::whereId($clientId)
                    ->where('organization_id', Auth::user()->organization_id)
                    ->first();

        // in case client exist
        if ($client)
        {
            // check template existence
            $template = Template::where("id",$data['template_id'])
                                ->where("organization_id",Auth::user()->organization_id)
                                ->first();

            if($template)
            {
                $temp = TemplateClient::whereClientId($clientId)
                                        ->where('template_id', $data['template_id'])
                                        ->first();
                if ($temp)
                {
                    return [
                        'data' =>null,
                        'status' => false,
                        'identifier_code' => 190003,
                        'status_code' => 400,
                        'message' => 'this template already exist with this client'
                    ];
                }
                else
                {
                    try
                    {
                        // begin database transaction
                        DB::beginTransaction();
                        $sections = Section::where('template_id', $data['template_id'])
                                        ->where('automatic_reminder', 1)
                                        ->get();
                        foreach ($sections as $section) {
                            $section->update([
                                'next_reminder' => now()->addDays($section->reminder_day)->setTimeFromTimeString($section->reminder_time)
                            ]);
                        }

                        // assign template to the client
                        $clientTemplate = TemplateClient::create([
                            'client_id' => $clientId,
                            'template_id' => $data['template_id']
                        ]);

                        // run event
                        event(new AssignClientTemplate($data['template_id'], $clientId));
                        // Happy ending :)
                        DB::commit();
                        return [
                            'data'            => $clientTemplate,
                            'status'          => true,
                            'identifier_code' => 190001,
                            'status_code'     => 200,
                            'message'         => 'Template assigned successfully'
                        ];
                    }
                    catch(\Exception $e)
                    {
                        // rollback!!!
                        DB::rollback();
                        Log::info("assign template to client issue : ".$e->getMessage());
                        return [
                            'data'            => null,
                            'status'          => false,
                            'identifier_code' => 190005,
                            'status_code'     => 400,
                            'message'         => 'Some thing went wrong, please try again later'
                        ];
                    }

                }

            }
            else
            {
                // template is not exist
                return [
                    'data'            => null,
                    'status'          => false,
                    'identifier_code' => 190004,
                    'status_code'     => 400,
                    'message'         => 'Template is not exist'
                ];
            }

        }
        else
        {
            // client is not exist
            return [

                'data'            => null,
                'status'          => false,
                'identifier_code' => 190002,
                'status_code'     => 400,
                'message'         => 'client is not exist'
            ];

        }

    }

    public function clientTemplate($clientId)
    {
        $templates = Template::whereHas('template_client',function($q) use($clientId){
            $q->where('client_id',$clientId);
        })->withTrashed(['sections' => function($w) use ($clientId){
            $w->whereHas('client_sections',function($z) use($clientId){
                $z->where('client_id',$clientId);
            });

            $w->with('team','client_sections');
        }]);

        $templates->when((request()->has('search') && !is_null(request('search'))),function($q){
            $q->where('title','LIKE','%'.request('search').'%');
        });

        $templates->when((request()->has('sort') && !is_null(request('sort'))),function($q){
            if(request('sort') == 'z-a'){
                $q->orderBy('title','DESC');
            } else {
                $q->orderBy('title','ASC');
            }
        });

        if(request()->has('paginated')){
            $result = $templates->paginate(config('app.per_page'));
        } else{
            $result = $templates->get();
        }

        return [
            'data' => ClientTemplateResource::collection($result),
            'status' => true,
            'identifier_code' => 192001,
            'status_code' => 200,
            'message' => 'Client Templates listed successfully'
        ];
    }

    public function updateClient($clientId, $data){
        $client=Client::whereId($clientId)->where('organization_id',Auth::user()->organization_id)->first();
        if($client){
            $updated_client=$client->update([
               'company_name' => $data['company_name'],
                'contact_person' => $data['contact_person'],
                'phone_number' => $data['phone_number'],
                'email' => $data['email'],
                'industry' => $data['industry'],
                'address' => $data['address'] ?? null,
                'additional_questions' => $data['additional_questions'] ?? null,
                'number_of_employees' => $data['number_of_employees'] ?? null,
            ]);
            return [
                'data' => new ClientResource($client),
                'status' => true,
                'identifier_code' => 195001,
                'status_code' => 200,
                'message' => 'client updated successfully'
            ];
        }
        else{
            return [
                'data' => NULL,
                'status' => false,
                'identifier_code' => 195002,
                'status_code' => 400,
                'message' => 'this client is not exist'
            ];

        }


    }

    public function showClient($clientId){
        $client=Client::where("id",$clientId)->where('organization_id', Auth::user()->organization_id)->first();
        if($client){
            return [
                'data' => new ClientResource($client),
                'status' => true,
                'identifier_code' => 195001,
                'status_code' => 200,
                'message' => 'show client data'
            ];
        }
        else{
            return [
                'data' => NULL,
                'status' => false,
                'identifier_code' => 195002,
                'status_code' => 400,
                'message' => 'this client is not exist'
            ];

        }
    }

    public function storeMRRServices($clientId,$data)
    {
        $opportunities=[];
        $mrrServices=[];
        $client = Client::whereId($clientId)
                        ->whereOrganizationId(Auth::user()->organization_id)
                        ->first();

        if($client){
            DB::beginTransaction();

            foreach($data['services'] as $item){
                if($item['is_opportunity']){
                    array_push($opportunities,$item);
                } else {
                    $temp = Service::select('unit_cost')->find($item['service_id']);
                    $item['cost'] = $temp->unit_cost;
                    $item['total_amount'] = $temp->unit_cost * $item['qty'];
                    array_push($mrrServices,Arr::only($item,['service_id','qty','cost','total_amount']));
                }
            }

            $client->MRRServices()->sync($mrrServices);



            foreach($opportunities as $opportunity){
                $temp = Service::select('unit_cost')->find($opportunity['service_id']);
                $client->opportunities()->updateOrCreate(['service_id'=>$opportunity['service_id']],[
                    'service_id' => $opportunity['service_id'],
                    'notes' => $opportunity['opportunity_notes'] ?? null,
                    'organization_id' => Auth::user()->organization_id,
                    'qty' => $opportunity['qty'] ?? null,
                    'total_amount' => $opportunity['qty'] * $temp->unit_cost
                ]);
            }

            DB::commit();
            DB::rollback();

            return [
                'data' => [new ClientResource($client)
                ,
                'services' => ClientServiceResource::collection($client->MRRServices),
                'opportunities' => ClientOpportunityResource::collection($client->opportunities)


                       ],
                'status' => true,
                'identifier_code' => 202001,
                'status_code' => 200,
                'message' => 'MRR services created successfully'
            ];
        } else {
            return [
                'data' => null,
                'status' => false,
                'identifier_code' => 202002,
                'status_code' => 400,
                'message' => 'this client is not exist'
            ];
        }
    }

    public function storeORRServices($clientId,$data)
    {
        $opportunities=[];
        $services=[];
        $client = Client::whereId($clientId)
                        ->whereOrganizationId(Auth::user()->organization_id)
                        ->first();

        if($client){
            DB::beginTransaction();

            foreach($data['services'] as $item){
                if($item['is_opportunity']){
                    array_push($opportunities,$item);
                } else {
                    $item['total_amount'] = $item['cost'] * $item['qty'];
                    // unset($item['is_opportunity']);
                    // if(key_exists('opportunity_notes',$item)){unset($item['opportunity_notes']);}
                    array_push($services,Arr::only($item,['service_id','qty','cost','total_amount']));
                }
            }

            $client->ORRServices()->sync($services);

            foreach($opportunities as $opportunity){
                $client->opportunities()->updateOrCreate(['service_id'=>$opportunity['service_id']],[
                    'service_id' => $opportunity['service_id'],
                    'notes' => $opportunity['opportunity_notes'] ?? null,
                    'organization_id' => Auth::user()->organization_id,
                    'qty' => $opportunity['qty'] ?? null,
                    'total_amount' => $opportunity['qty'] * $opportunity['cost']
                ]);
            }

            DB::commit();
            DB::rollback();

            return [
                'data' => new ClientResource($client),
                'status' => true,
                'identifier_code' => 202001,
                'status_code' => 200,
                'message' => 'ORR services created successfully'
            ];
        } else {
            return [
                'data' => null,
                'status' => false,
                'identifier_code' => 202002,
                'status_code' => 400,
                'message' => 'this client is not exist'
            ];
        }
    }


    public function listMRRServices($clientId){

        $client = Client::whereId($clientId)
        ->whereOrganizationId(Auth::user()->organization_id)
        ->first();

        if($client){

       /*  $opportunities = $client->opportunities()->whereHas('service',function($q){
            $q->where('type','MRR')->with('service');
        });

        $opportunities = $opportunities->paginate(config('app.per_page')); */
        $services = Service::whereOrganizationId(Auth::user()->organization_id)
                            ->where('type','MRR')
                            ->Where(function ($q) use ($client){
                                $q->whereHas("ClientMRRServices",function($qq) use ($client){
                                    $qq->where("client_id",$client->id);
                                })->orWhereHas("opportunities",function($qq) use ($client){
                                    $qq->where("client_id",$client->id);
                                });
                            })->with(["ClientMRRServices"=>function($callback) use ($client){
                                        $callback->where("client_id",$client->id);
                            },"opportunities"=>function($callback) use ($client){
                                         $callback->where("client_id",$client->id);
                            }]);

            return [
                'data' => [
                    'services' => QuoteMRRServiceResource::collection($services->paginate(config('app.per_page')))->response()->getData(true),
                ],
                'status' => true,
                'identifier_code' => 206001,
                'status_code' => 200,
                'message' => 'Client MRR Services Listed Successfully'
            ];

        }
        else{
            return [
                'data' => null,
                'status' => false,
                'identifier_code' => 206002,
                'status_code' => 400,
                'message' => 'Client is not exist'
            ];
        }


    }

    public function listORRServices($clientId){

        $client = Client::whereId($clientId)
        ->whereOrganizationId(Auth::user()->organization_id)
        ->first();
        if($client){

        /* $opportunities = $client->opportunities()->whereHas('service',function($q){
            $q->where('type','ORR');
        })->with('service');
        $opportunities = $opportunities->paginate(config('app.per_page')); */

        $services = Service::whereOrganizationId(Auth::user()->organization_id)
                            ->where('type','ORR')
                            ->Where(function ($q) use ($client){
                                $q->whereHas("ClientORRServices",function($qq) use ($client){
                                    $qq->where("client_id",$client->id);
                                })->orWhereHas("opportunities",function($qq) use ($client){
                                    $qq->where("client_id",$client->id);
                                });
                            })->with(["ClientORRServices"=>function($callback) use ($client){
                                        $callback->where("client_id",$client->id);
                            },"opportunities"=>function($callback) use ($client){
                                         $callback->where("client_id",$client->id);
                            }]);
            return [
                 'data' => [
                    'services' => QuoteORRServiceResource::collection($services->paginate(config('app.per_page')))->response()->getData(true),
                ],
                'status' => true,
                'identifier_code' => 207001,
                'status_code' => 200,
                'message' => 'Client ORR Services Listed Successfully'
            ];

        }
        else{
            return [
                'data' => null,
                'status' => false,
                'identifier_code' => 207002,
                'status_code' => 400,
                'message' => 'Client is not exist'
            ];
        }


    }

    public function calculateClientProfit($clientId)
    {
        $client = Client::whereId($clientId)
                        ->whereOrganizationId(Auth::user()->organization_id)
                        ->first();
        if($client){
            $mrrServices=new Collection();
            $orrServices=new Collection();
            $quotations = ClientQuote::where('client_id',$client->id)
                                     ->where('is_sent',1)
                                     ->where('status','accepted')
                                     ->when(request()->has('year') && request('year') == 'this_year', function($q){
                                        $q->whereYear('updated_at',now()->year);
                                     })
                                     ->when(request()->has('year') && request()->has('month') && !is_null(request('month')) && request('year') != 'this_year' && !is_null(request('year')), function($q){
                                        $q->whereYear('updated_at',request('year'))->whereMonth('updated_at',request('month'));
                                     })
                                     ->get();

            $oldData = $this->getOldProfitData();

            $oldMrrProfit = $oldData['mrrProfit'] == 0 ? 1 : $oldData['mrrProfit'];
            $oldOrrProfit = $oldData['orrProfit'] == 0 ? 1 : $oldData['orrProfit'];
            $oldPureProfit = $oldData['pureProfit'] == 0 ? 1 : $oldData['pureProfit'];


            if($quotations->count() == 0){
                return [
                    'data' => [
                        'MRR_profit' => ['percentage_value' => null, 'growth_rate'=>-$oldData['mrrProfit']/$oldMrrProfit],
                        'ORR_profit' => ['percentage_value' => null, 'growth_rate'=>-$oldData['orrProfit']/$oldOrrProfit],
                        'pure_profit' => ['percentage_value' => null, 'growth_rate'=>-$oldData['pureProfit']/$oldPureProfit]
                    ],
                    'status' => false,
                    'identifier_code' => 213003,
                    'status_code' => 200,
                    'message' => 'this client was not available on this period of time'
                ];
            }
            foreach($quotations as $quote){
                foreach($quote->services as $service){
                    if($service['type'] == 'MRR'){
                        $mrrServices->push($service);
                    } else {
                        $orrServices->push($service);
                    }
                }
            }
            $mrrProfit = $mrrServices->sum('total_amount');
            $orrProfit = $orrServices->sum('total_amount');
            return [
                'data' => [
                    'MRR_profit' => ['percentage_value' => $mrrProfit, 'growth_rate'=>($mrrProfit-$oldData['mrrProfit'])/$oldMrrProfit],
                    'ORR_profit' => ['percentage_value' => $orrProfit, 'growth_rate'=>($orrProfit-$oldData['orrProfit'])/$oldOrrProfit],
                    'pure_profit' => ['percentage_value' => $mrrProfit - $orrProfit, 'growth_rate'=>(($mrrProfit-$orrProfit)-$oldData['pureProfit'])/$oldPureProfit]
                ],
                'status' => true,
                'identifier_code' => 213001,
                'status_code' => 200,
                'message' => 'Client Profit'
            ];
        } else {
            return [
                'data' => null,
                'status' => false,
                'identifier_code' => 213002,
                'status_code' => 400,
                'message' => 'Client not found'
            ];
        }
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function calculateAllClientsProfit()
        {
            $quotations = ClientQuote::where('organization_id',Auth::user()->organization_id)
                                    ->where('is_sent',1)
                                    ->where('status','accepted')
                                    ->when(request()->has('year') && request('year') == 'this_year', function($q){
                                        $q->whereYear('updated_at',now()->year);
                                    })
                                    ->when(request()->has('year') && request()->has('month') && !is_null(request('month')) && request('year') != 'this_year' && !is_null(request('year')), function($q){
                                        $q->whereYear('updated_at',request('year'))->whereMonth('updated_at',request('month'));
                                    })
                                    ->get();

            $oldData = $this->getOldProfitData();

            $oldMrrProfit = $oldData['mrrProfit'] == 0 ? 1 : $oldData['mrrProfit'];
            $oldOrrProfit = $oldData['orrProfit'] == 0 ? 1 : $oldData['orrProfit'];
            $oldPureProfit = $oldData['pureProfit'] == 0 ? 1 : $oldData['pureProfit'];

            if($quotations->count() == 0){
                return [
                    'data' => [
                        'MRR_profit' => ['percentage_value' => null, 'growth_rate'=>-$oldData['mrrProfit']/$oldMrrProfit],
                        'ORR_profit' => ['percentage_value' => null, 'growth_rate'=>-$oldData['orrProfit']/$oldOrrProfit],
                        'pure_profit' => ['percentage_value' => null, 'growth_rate'=>-$oldData['pureProfit']/$oldPureProfit]
                    ],
                    'status' => false,
                    'identifier_code' => 214002,
                    'status_code' => 200,
                    'message' => 'no accepted quotations'
                ];
            }

            $mrrServices=new Collection();
            $orrServices=new Collection();

            foreach($quotations as $quote){
                foreach($quote->services as $service){
                    if($service['type'] == 'MRR'){
                        $mrrServices->push($service);
                    } else {
                        $orrServices->push($service);
                    }
                }
            }

            $mrrProfit = $mrrServices->sum('total_amount');
            $orrProfit = $orrServices->sum('total_amount');
            return [
                'data' => [
                    'MRR_profit' => ['percentage_value' => $mrrProfit, 'growth_rate'=>($mrrProfit-$oldData['mrrProfit'])/$oldMrrProfit],
                    'ORR_profit' => ['percentage_value' => $orrProfit, 'growth_rate'=>($orrProfit-$oldData['orrProfit'])/$oldOrrProfit],
                    'pure_profit' => ['percentage_value' => $mrrProfit - $orrProfit, 'growth_rate'=>(($mrrProfit-$orrProfit)-$oldData['pureProfit'])/$oldPureProfit]
                ],
                'status' => true,
                'identifier_code' => 214001,
                'status_code' => 200,
                'message' => 'all clients profit'
            ];
        }

        public function getOldProfitData($clientId = null)
        {
            if($clientId){
                 $oldQuotations = ClientQuote::where('client_id',$clientId)
                                            ->where('is_sent',1)
                                            ->where('status','accepted')
                                            ->whereMonth('updated_at','<',now()->month)
                                            ->when(request()->has('year') && request('year') == 'this_year', function($q){
                                                $q->whereYear('updated_at',now()->year-1);
                                            })
                                            ->when(request()->has('year') && request()->has('month') && !is_null(request('month')) && request('year') != 'this_year' && !is_null(request('year')), function($q){
                                                $q->whereYear('updated_at',request('year'))->whereMonth('updated_at',request('month')-1);
                                            })
                                            ->get();
            } else {
                $oldQuotations = ClientQuote::where('organization_id',Auth::user()->organization_id)
                                            ->where('is_sent',1)
                                            ->where('status','accepted')
                                            ->whereMonth('updated_at','<',now()->month)
                                            ->when(request()->has('year') && request('year') == 'this_year', function($q){
                                                $q->whereYear('updated_at',now()->year-1);
                                            })
                                            ->when(request()->has('year') && request()->has('month') && !is_null(request('month')) && request('year') != 'this_year' && !is_null(request('year')), function($q){
                                                $q->whereYear('updated_at',request('year'))->whereMonth('updated_at',request('month')-1);
                                            })
                                            ->get();
            }


            if($oldQuotations->count() == 0){
                return [
                    'mrrProfit' => 0,
                    'orrProfit' => 0,
                    'pureProfit' => 0
                ];
            }

            $oldMrrServices=new Collection();
            $oldOrrServices=new Collection();

            foreach($oldQuotations as $quote){
                foreach($quote->services as $service){
                    if($service['type'] == 'MRR'){
                        $oldMrrServices->push($service);
                    } else {
                        $oldOrrServices->push($service);
                    }
                }
            }

            $oldMrrProfit = $oldMrrServices->sum('total_amount') ;
            $oldOrrProfit = $oldOrrServices->sum('total_amount') ;
            $oldPureProfit = $oldMrrProfit - $oldOrrProfit;

            // dd($oldPureProfit);

            return [
                'mrrProfit' => $oldMrrProfit,
                'orrProfit' => $oldOrrProfit,
                'pureProfit' => $oldPureProfit
            ];
        }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function clientProfitsTable($clientId)
        {
            $client = Client::whereId($clientId)
                            ->whereOrganizationId(Auth::user()->organization_id)
                            ->where('status','Active')
                            ->first();

            if($client){
                $quote = ClientQuote::where('client_id',$client->id)
                                    ->where('is_sent',1)
                                    ->where('status','accepted')
                                    ->when(request()->has('year') && !is_null(request('year')) && request()->has('month') && !is_null(request('month')),function($q){
                                        $q->whereMonth('updated_at',request('month'))->whereYear('updated_at',request('year'));
                                    })
                                    ->latest()
                                    ->first();
                    ;

                if(!$quote){
                    return [
                        'data' => [
                            'client_name' => $client->contact_person,
                            'services' => null,
                            'true_profit' => [
                                'total_qty' =>  null,
                                'total_cost' => null,
                                'total_amount' => null
                            ]
                        ],
                        'status' => false,
                        'identifier_code' => 215003,
                        'status_code' => 200,
                        'message' => 'no accepted quotations on time period'
                    ];
                }

                $collection = collect($quote->services);
                $total_qty = $collection->sum('qty');
                $total_cost = $collection->sum('unit_cost');
                $total = $collection->sum('total_amount');


                $MRR_services=[];
                $ORR_services=[];
                foreach($collection as $service){
                    $service['type']=='MRR' ?  array_push($MRR_services,$service) :array_push($ORR_services,$service);
                }

                return [
                    'data' => [

                        'client_name' => $client->contact_person,
                        'MRR_services'=> $MRR_services,
                        'ORR_services' => $ORR_services,
                        'Mrr_total_qty'=>collect($MRR_services)->sum('qty'),
                        'ORR_total_qty'=>collect($ORR_services)->sum('qty'),
                        'MRR_total_cost'=>collect($MRR_services)->sum('unit_cost'),
                        'ORR_total_cost'=>collect($ORR_services)->sum('unit_cost'),
                        'MRR_amount'=>collect($MRR_services)->sum('total_amount'),
                        'ORR_amount'=>collect($ORR_services)->sum('total_amount'),
                        'true_profit'=>collect($MRR_services)->sum('total_amount')-collect($ORR_services)->sum('total_amount')
                        ],

                    'status' => true,
                    'identifier_code' => 215001,
                    'status_code' => 200,
                    'message' => 'profit table'
                ];

            } else {
                return [
                    'data' => null,
                    'status' => false,
                    'identifier_code' => 215002,
                    'status_code' => 400,
                    'message' => 'Client Not Found'
                ];
            }
        }

    public function listClientTasks($data,$clientId)
    {
        $section = ClientSection::where('section_id',$data['section_id'])->where('client_id',$clientId)->first();
        if ($section) {

            $client = Client::whereId($clientId)
                            ->whereOrganizationId(Auth::user()->organization_id)
                            ->with(['tasks' =>function($q) use($data){
                                $q->whereHas('task' , function($w) use($data){
                                    $w->where('section_id',$data['section_id']);
                                    $w->orderBy('display_order','ASC');
                                });
                                $q->with('task');
                            }])
                            ->first();

            if(!$client){
                return [
                    'data' => null,
                    'status' => false,
                    'identifier_code' => 218003,
                    'status_code' => 400,
                    'message' => 'client not found'
                ];
            }

            return [
                'data' => [
                    'section' => [
                        'progress' => $section->progress,
                        'due_date' => $section->due_date,
                        'team' => $section->section->team->name,
                        'client_name' => $client->contact_person
                    ],
                    'tasks' => ClientTaskResource::collection($client->tasks)
                ],
                'status' => true,
                'identifier_code' => 218001,
                'status_code' => 200,
                'message' => 'Client Tasks'
            ];
        } else {
            return [
                'data' => null,
                'status' => false,
                'identifier_code' => 218002,
                'status_code' => 400,
                'message' => 'section not with this client'
            ];
        }
    }

    public function changeSectionDueDate($data,$clientId)
    {
        $client = Client::whereId($clientId)
                        ->whereOrganizationId(Auth::user()->organization_id)
                        ->first();

        if($client){
            $section = ClientSection::where('section_id',$data['section_id'])
                                    ->where('client_id',$clientId)
                                    ->first();
            if($section){
                $section->update([
                    'due_date' => $data['due_date']
                ]);
                return [
                    'data' => $section->fresh(),
                    'status' => true,
                    'identifier_code' => 220001,
                    'status_code' => 200,
                    'message' => 'due date changed successfully'
                ];
            } else {
                return [
                    'data' => null,
                    'status' => false,
                    'identifier_code' => 220003,
                    'status_code' => 400,
                    'message' => 'this section not exist with this client'
                ];
            }
        } else {
            return [
                'data' => null,
                'status' => false,
                'identifier_code' => 220002,
                'status_code' => 400,
                'message' => 'Client Not Found'
            ];
        }


    }



    }

?>
