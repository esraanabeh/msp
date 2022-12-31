<?php

namespace Modules\Templates\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Templates\Repositories\Repos\TemplateRepository;
use Modules\Templates\Http\Requests\NewSectionRequest;
use Modules\Templates\Http\Requests\updateSectionRequest;
use Modules\Templates\Http\Requests\ClientTemplateRequest;
use Illuminate\Routing\Controller;
use App\Http\Traits\ApiResponseTrait;
use Modules\Templates\Http\Requests\NewTemplateRequest;
use Modules\Templates\Http\Requests\UpdateTemplateRequest;

class TemplatesController extends Controller
{

    use ApiResponseTrait;
    private $templateRepository;


    public function __construct(TemplateRepository $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $result = $this->templateRepository->listallTemplates();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function myTemplate()
    {
        $result = $this->templateRepository->listMyTemplates();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function listSections($templateId)
    {
        $result = $this->templateRepository->listallSections($templateId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('templates::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */



     // create new Template with section
     public function createTemplate(NewTemplateRequest $request)
     {
         $result = $this->templateRepository->createTemplate($request->validated());
         return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
     }

      // create new Template Of Specific Client
      public function clientTemplate(ClientTemplateRequest $request ,$clientId)
      {
          $result = $this->templateRepository->createClientTemplate($request->validated(),$clientId);
          return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
      }

     public function createNewSection(NewSectionRequest $request)
     {
         $result = $this->templateRepository->addNewSection($request);
         return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
     }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function showTemplate($id)
    {
        $result = $this->templateRepository->showTemplate($id);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }


    public function showSection($id)
    {
        $result = $this->templateRepository->showSection($id);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('templates::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateTemplateRequest $request, $id)
    {
        $result = $this->templateRepository->updateTemplate($id ,  $request->validated());
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */


    public function destroySection($id)
    {
        $result = $this->templateRepository->deleteSection($id);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function destroyTemplate($id)
    {
        $result = $this->templateRepository->deleteTemplate($id);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }
    public function updateSection(updateSectionRequest $request,$id)
    {
        $result = $this->templateRepository->updateSection($id ,  $request);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }
}
