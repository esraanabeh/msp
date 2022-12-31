<?php

namespace Modules\Industries\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use App\Http\Traits\ApiResponseTrait;
use Modules\Industries\Http\Requests\CreateIndustryRequest;
use Modules\Industries\Http\Requests\UpdateIndustryRequest;
use Modules\Industries\Repositories\Repos\IndustryRepository;
use Modules\Industries\Models\Industry;
use Illuminate\Support\Facades\Auth;
use Modules\Organization\Models\Organization;
use Modules\Industries\Http\Resources\IndustryResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class IndustriesController extends Controller
{

    use ApiResponseTrait;

    private $industryRepository;
    public function __construct(IndustryRepository $industryRepository)
    {
        $this->industryRepository = $industryRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $result= $this->industryRepository->getIndustries();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('industries::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CreateIndustryRequest $request)
    {

        $org=auth()->user()->organization_admin;
        if ($org && $org->organization_id === (int)  Auth::user()->organization_id){
        $result = $this->industryRepository->createIndustry($request);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
        }
    else{
        return $this->apiResponse( 'this user is not orgnization admin','false','128002',403,'error' );


        }

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('industries::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('industries::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateIndustryRequest $request)
    {
        $result= $this->industryRepository->updateIndustry( $request);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }




    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $result= $this->industryRepository->deleteIndustry($id);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }
}
