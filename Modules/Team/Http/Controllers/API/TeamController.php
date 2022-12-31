<?php

namespace Modules\Team\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Traits\ApiResponseTrait;
use Modules\Team\Http\Requests\StoreTeamRequest;
use Modules\Team\Http\Requests\UpdateTeamRequest;
use Modules\Team\Http\Requests\TeamStatusRequest;
use Modules\Team\Repositories\Repos\TeamRepository;
use Modules\Team\Models\Team;
use App\Models\User;
use Modules\Team\Http\Resources\TeamResource;

class TeamController extends Controller
{
    use ApiResponseTrait;

    private $teamRepository;
    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    public function index()
    {
        $result= $this->teamRepository->getTeams();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function store(StoreTeamRequest $request)
    {
        $result = $this->teamRepository->createTeam($request);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);

    }

    public function update(UpdateTeamRequest $request, $id)
    {
        $result= $this->teamRepository->updateTeam( $id ,  $request);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function updateStatus(TeamStatusRequest $request ){

        $result= $this->teamRepository->updateTeamStatus( $request);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }


    public function destroy($id)
    {
        $result = $this->teamRepository->deleteTeam($id);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }


}
