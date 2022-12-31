<?php

namespace Modules\Services\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use App\Http\Traits\ApiResponseTrait;
use Modules\Services\Http\Requests\CreateServiceRequest;
use Modules\Services\Http\Requests\UpdateServiceRequest;
use Modules\Services\Http\Requests\DefaultRequest;
use Modules\Services\Http\Requests\UploadFileRequest;
use Modules\Services\Repositories\Repos\ServiceRepository;
use Modules\Services\Models\Service;
use Modules\Services\Models\MasterServiceAgreement;
use Modules\Organization\Models\Organization;
use Modules\Services\Http\Resources\ServiceResource;
use Modules\Services\Http\Resources\MasterAgreementResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class ServicesPricingController extends Controller
{

    use ApiResponseTrait;
    private $serviceRepository;


    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    // list services
    public function index()
    {
        $result= $this->serviceRepository->getServices();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    // create new service
    public function store(CreateServiceRequest $request)
    {
        $result = $this->serviceRepository->createService($request);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    // update existing service
    public function update(UpdateServiceRequest $request)
    {
        $result= $this->serviceRepository->updateService(  $request);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    // delete existing service
    public function delete(Request $request, $id)
    {
        $result= $this->serviceRepository->deleteService($id,$request);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function listMRRQuestions()
    {
        $result = $this->serviceRepository->listMRRServices();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function listORRQuestions()
    {
        $result = $this->serviceRepository->listORRServices();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    }
