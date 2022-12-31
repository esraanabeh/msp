<?php

namespace Modules\Opportunity\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Opportunity\Http\Requests\OpportunityRequest;
use Modules\Opportunity\Repositories\Interfaces\IOpportunityRepository;

class OpportunityController extends Controller
{

    use ApiResponseTrait;

    public function __construct(IOpportunityRepository $opportunity)
    {
        $this->opportunity = $opportunity;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
       $result = $this->opportunity->listOpportunities();
       return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function preview($id)
    {

        $result = $this->opportunity->previewOpportunities($id);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }


    public function previewClientOpportunity($id)
    {
        $result = $this->opportunity->previewClientOpportunities($id);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(OpportunityRequest $request)
    {
       $result = $this->opportunity->createOpportunities($request);
       return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('opportunity::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('opportunity::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(OpportunityRequest $request, $id)
    {
        $result = $this->opportunity->updateOpportunity($id , $request);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $result = $this->opportunity->deleteOpportunity($id);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }
}
