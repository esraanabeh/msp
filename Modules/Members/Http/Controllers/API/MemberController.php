<?php

namespace Modules\Members\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponseTrait;
use Modules\Members\Http\Requests\StoreMemberRequest;
use Modules\Members\Http\Requests\UpdateMemberRequest;
use Modules\Members\Http\Requests\MemberStatusRequest;
use Modules\Members\Repositories\Repos\MemberRepository;
use Modules\Members\Models\Member;
use Modules\Team\Models\Team;
use App\Models\User;
use Modules\Members\Http\Resources\UpdateMemberResource;
use Modules\Members\Http\Resources\MemberResource;
use Illuminate\Routing\Controller;

class MemberController extends Controller
{

    use ApiResponseTrait;

    private $memberRepository;
    public function __construct(MemberRepository $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $result= $this->memberRepository->getMembers();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('members::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(StoreMemberRequest $request)
    {
        $result = $this->memberRepository->createMember($request);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('members::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('members::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateMemberRequest $request, $id)
    {
        $result= $this->memberRepository->updateMember( $id ,  $request);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $member= $this->memberRepository->deleteMember($id);
        return $this->apiResponse( null,'true','110003',200,'member deleted successfully' );
    }



    public function update_status(MemberStatusRequest $request ){

        $result= $this->memberRepository->updateMemberStatus(   $request);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }
}
