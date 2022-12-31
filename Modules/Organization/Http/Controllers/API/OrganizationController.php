<?php

namespace Modules\Organization\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Organization\Http\Requests\OrganizatonRequest;
use Modules\Organization\Http\Requests\UpdateInfoRequest;
use Modules\Organization\Http\Requests\UploadLogoRequest;
use Modules\Organization\Http\Requests\TermsRequest;
use Modules\Organization\Repositories\OrganizationRepository;
use App\Http\Traits\ApiResponseTrait;
use Modules\Organization\Http\Resources\OrganizationResource;
use Modules\Organization\Events\OrganizationCreatedAfter;
use Auth;
class OrganizationController extends Controller
{
    use ApiResponseTrait;

    private $organizationRepository;
    public function __construct(OrganizationRepository $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    public function create(OrganizatonRequest $request)
    {
        if(Auth::user()->organization_admin){
            return $this->apiResponse( null,false,'106002',400,'You Already Have Workspace' );
        }
        $org = $this->organizationRepository->createOrganization($request);
        event(new OrganizationCreatedAfter($org,auth()->user()));
        return $this->apiResponse( new OrganizationResource($org),'true','106001',200,'Workspace created successfully' );
    }

    public function update(UpdateInfoRequest $request)
    {
        $organization= $this->organizationRepository->updateProfile($request);
        return $this->apiResponse(new OrganizationResource($organization),'true','113002',200,'organization updated successfully' );

    }

    public function listInfo(Request $request){
        $info = $this->organizationRepository->getOrgInfo($request);
        return $this->apiResponse($info['data'] , $info['status'] , $info['identifier_code'] , $info['status_code'] , $info['message']);

    }

    


    public function upload(UploadLogoRequest $request){
     $logo = $this->organizationRepository->uploadLogo($request);
     return $this->apiResponse($logo['data'] , $logo['status'] , $logo['identifier_code'] , $logo['status_code'] , $logo['message']);

    }

    public function updateLogo(UploadLogoRequest $request){
        $logo = $this->organizationRepository->updateOrgLogo($request);
        return $this->apiResponse($logo['data'] , $logo['status'] , $logo['identifier_code'] , $logo['status_code'] , $logo['message']);

    }


    public function getLogo(Request $request){
        $logo = $this->organizationRepository->getOrgLogo($request);
        return $this->apiResponse($logo['data'] , $logo['status'] , $logo['identifier_code'] , $logo['status_code'] , $logo['message']);

       }

       public function updateOrgTerms(TermsRequest $request){
        $logo = $this->organizationRepository->updateTerms($request);
        return $this->apiResponse($logo['data'] , $logo['status'] , $logo['identifier_code'] , $logo['status_code'] , $logo['message']);

       }

       public function getOrgTerms(Request $request){
        $logo = $this->organizationRepository->getTerms($request);
        return $this->apiResponse($logo['data'] , $logo['status'] , $logo['identifier_code'] , $logo['status_code'] , $logo['message']);

       }

}
