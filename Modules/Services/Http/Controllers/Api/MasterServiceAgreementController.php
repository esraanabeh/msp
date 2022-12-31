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

class MasterServiceAgreementController extends Controller
{

    use ApiResponseTrait;
    private $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository){
        $this->serviceRepository = $serviceRepository;
    }
   

    public function store(UploadFileRequest $request){
        $result = $this->serviceRepository->createMasterAgreement($request);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function index(Request $request){
        $result= $this->serviceRepository->getServicesAgreements();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);

    }


    public function delete ($id){
        $result= $this->serviceRepository->deleteServiceAgreement($id);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function updateDefault(Request $request){
        $result = $this->serviceRepository->updateMasterAgreementDefault($request);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }
}
