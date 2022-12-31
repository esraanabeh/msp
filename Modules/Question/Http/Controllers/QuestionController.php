<?php

namespace Modules\Question\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Question\Http\Requests\QuestionRequest;
use Modules\Question\Http\Resources\QuestionResource;
use Modules\Question\Models\Question;
use Modules\Question\Repositories\Interfaces\IQuestionRepository;
use Modules\Question\Repositories\Repos\QuestionRepository;
use PHPUnit\Framework\MockObject\Api;

class QuestionController extends Controller
{
    use ApiResponseTrait;

    public function __construct(QuestionRepository $iQuestionRepository)
    {
        $this->question = $iQuestionRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $result = $this->question->listQuestions();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('question::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(QuestionRequest $request)
    {
        $result = $this->question->findOrCreateQuestion($request->validated());
        return $this->apiResponse(new QuestionResource($result),true,150001,200,"Question created successfully");
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('question::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('question::edit');
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
        Question::find($id)->delete();
        return $this->apiResponse(null,true,151001,200,"Question deleted successfully");
    }

    public function listMRRQuestions()
    {
        $result = $this->question->listMRRServices();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function listORRQuestions($clientId)
    {
        $result = $this->question->listORRServices($clientId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }
}
