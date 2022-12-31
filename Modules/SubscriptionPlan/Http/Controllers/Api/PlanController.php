<?php

namespace Modules\SubscriptionPlan\Http\Controllers\API;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SubscriptionPlan\Models\Plan;
use Modules\SubscriptionPlan\Repositories\SubscriptionPlanRepository;

class PlanController extends Controller
{
    use ApiResponseTrait;

    private $subscriptionPlanRepository;
    public function __construct(SubscriptionPlanRepository $subscriptionPlanRepository)
    {
        $this->subscriptionPlanRepository = $subscriptionPlanRepository;
    }
    
    public function index()
    {
        $info = $this->subscriptionPlanRepository->listPlans();    
        return $this->apiResponse($info['data'] , $info['status'] , $info['identifier_code'] , $info['status_code'] , $info['message']);
    }

    public function getMyPlan(){
        $info = $this->subscriptionPlanRepository->getMyPlan();    
        return $this->apiResponse($info['data'] , $info['status'] , $info['identifier_code'] , $info['status_code'] , $info['message']);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Plan $plan, Request $request)
    {   
        $paymentMethods = $request->user()->paymentMethods();

        $intent = $request->user()->createSetupIntent();
        
        return view('plans.show', compact('plan', 'intent'));
    }
    

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
