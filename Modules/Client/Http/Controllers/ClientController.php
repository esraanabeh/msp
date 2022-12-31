<?php

namespace Modules\Client\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Client\Http\Requests\ClientTemplateRequest;
use Illuminate\Routing\Controller;
use App\Models\User;
use Modules\Client\Http\Requests\ClientRequest;
use Modules\Client\Http\Requests\ClientUpdateRequest;
use Modules\Client\Http\Requests\ClientStatusRequest;
use Modules\Client\Http\Requests\ClientQuestionRequest;
use Modules\Client\Http\Requests\ORRRequest;
use Modules\Client\Repositories\Interfaces\IClientRepository;
use Modules\Client\Events\CreateClientEvent;
use Modules\Client\Events\AcceptQuoteEvent;
use Modules\Client\Http\Requests\ClientTasksRequest;
use Modules\Client\Http\Requests\StoreMRRServiceRequest;
use Modules\Client\Http\Requests\StoreORRServiceRequest;
use Modules\Client\Http\Requests\UpdateQuestionRequest;
use Modules\Client\Http\Requests\UpdateSectionDueDate;

class ClientController extends Controller
{
    use ApiResponseTrait;

    public function __construct(IClientRepository $client)
    {
        $this->client = $client;
    }

    public function store(ClientRequest $request)
    {
        $result = $this->client->createClient($request->validated());
        event(new CreateClientEvent($result,auth()->user()));
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function storeClientQuestions(ClientQuestionRequest $request,$clientId)
    {
        $result = $this->client->handleClientQuestions($clientId,$request->validated());
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function updateClientQuestions(UpdateQuestionRequest $request,$clientId)
    {
        $result = $this->client->updateClientQuestions($clientId,$request);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function deleteClientQuestions($questionId)
    {
        $result = $this->client->deleteClientQuestions($questionId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function listClientQuestions($clientId)
    {
        $result = $this->client->listClientQuestions($clientId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    // public function storeClientORRQuestions(ORRRequest $request,$clientId)
    // {
    //     $result = $this->client->handleClientQuestions($clientId,$request->validated()['questions']);
    //     return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    // }

    public function index()
    {
       $result = $this->client->listClients();
       return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function changeClientStatus(ClientStatusRequest $request,$clientId)
    {
        $result = $this->client->changeClientStatus($request->validated(),$clientId);
        if($result['status_code']==200){
            $or_users=User::where('organization_id',auth()->user()->organization_id)
            ->where('id', '!=', auth()->user()->id)->where('status',1)->where('is_verified',1)->get();
            event(new AcceptQuoteEvent($result,$or_users));
        }
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function update(ClientUpdateRequest $request,$clientId)
    {
        $result = $this->client->updateClient($clientId,$request->validated());
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function destroy($clientId)
    {
        $result = $this->client->deleteClient($clientId);
       return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

       public function assignTemplateToClient(ClientTemplateRequest $request,$clientId)
    {
        $result = $this->client->assignClientTemplate($request->validated(),$clientId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function listClientTemplates($clientId)
    {
        $result = $this->client->clientTemplate($clientId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function show($clientId)
    {
        $result = $this->client->showClient($clientId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function storeMRRServices(StoreMRRServiceRequest $request, $clientId)
    {
        $result = $this->client->storeMRRServices($clientId, $request->validated());
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function storeORRServices(StoreORRServiceRequest $request, $clientId)
    {
        $result = $this->client->storeORRServices($clientId, $request->validated());
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function listMRRServices( $clientId)
    {
        $result = $this->client->listMRRServices($clientId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function listORRServices( $clientId)
    {
        $result = $this->client->listORRServices($clientId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function clientProfit($clientId)
    {
        $result = $this->client->calculateClientProfit($clientId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function allClientsProfit()
    {
        $result = $this->client->calculateAllClientsProfit();
      return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }
    public function profitsTable($clientId)
    {
        $result = $this->client->clientProfitsTable($clientId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function monthlyProfitTable($clientId)
    {
        $result = $this->client->clientMonthlyProfitTable($clientId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function clientTasks(ClientTasksRequest $request,$clientId)
    {
        $result = $this->client->listClientTasks($request->validated(),$clientId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function changeSectionDueDate(UpdateSectionDueDate $request, $clientId)
    {
        $result = $this->client->changeSectionDueDate($request->validated(),$clientId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }
}
