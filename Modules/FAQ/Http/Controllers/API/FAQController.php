<?php

namespace Modules\FAQ\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\FAQ\Http\Requests\FAQRequest;
use Modules\FAQ\Http\Requests\UpdateFAQRequest;
use Modules\FAQ\Http\Requests\UpdateCategoryRequest;
use Modules\FAQ\Repositories\Repos\FAQRepository;
use Illuminate\Routing\Controller;
use App\Http\Traits\ApiResponseTrait;
use Modules\FAQ\Http\Requests\CategoryRequest;

class FAQController extends Controller
{

    use ApiResponseTrait;

    private $FAQRepository;
    public function __construct(FAQRepository $FAQRepository)
    {
        $this->FAQRepository = $FAQRepository;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function listFAQ($id)
    {
        $FAQ= $this->FAQRepository->getFAQ($id);
        return $this->apiResponse($FAQ['data'] , $FAQ['status'] , $FAQ['identifier_code'] , $FAQ['status_code'] , $FAQ['message']);
    }

    public function listCategories()
    {
        $categories= $this->FAQRepository->getCategories();
        return $this->apiResponse($categories['data'] , $categories['status'] , $categories['identifier_code'] , $categories['status_code'] , $categories['message']);
    }

    public function destroyCategory($id)
    {
        $categories= $this->FAQRepository->destroyCategory($id);
        return $this->apiResponse($categories['data'] , $categories['status'] , $categories['identifier_code'] , $categories['status_code'] , $categories['message']);
    }

    public function destroyQuestion($id)
    {
        $questions= $this->FAQRepository->destroyQuestion($id);
        return $this->apiResponse($questions['data'] , $questions['status'] , $questions['identifier_code'] , $questions['status_code'] , $questions['message']);
    }

     public function createCategory(CategoryRequest $request)
    {
        $result = $this->FAQRepository->createCategory($request->validated());
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function storeFAQ(FAQRequest $request ,$categoryId)
    {
        $result = $this->FAQRepository->createFAQ($request->validated(),$categoryId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
     // update existing FAQ
     public function update(UpdateFAQRequest $request,$id)
     {
         $result= $this->FAQRepository->updateFAQ($request ,$id);
         return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
     }

     // update existing FAQ
     public function updateCategory(UpdateCategoryRequest $request,$id)
     {
         $result= $this->FAQRepository->updateCategory($request ,$id);
         return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
     }


}
