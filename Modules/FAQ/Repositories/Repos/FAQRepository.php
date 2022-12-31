<?php

namespace Modules\FAQ\Repositories\Repos;

use Modules\FAQ\Repositories\Interfaces\IFAQRepository;
use Modules\FAQ\Models\Category;
use Modules\FAQ\Models\FAQ;
use Illuminate\Support\Facades\Auth;
use Modules\FAQ\Http\Resources\CategoryResource;
use Modules\FAQ\Http\Resources\FAQResource;
use Modules\FAQ\Http\Resources\FAQWithCatResource;



class FAQRepository implements IFAQRepository{



    public function getCategories()
    {
        $qb= Category::where('organization_id',Auth::user()->organization_id)->orderBy('id','DESC');
        $data = $qb->paginate(config('app.per_page'));
        return [
            'data' => CategoryResource::collection($data)->response()->getData(true),
            'status' => true,
            'identifier_code' => 139001,
            'status_code' => 200,
            'message' => 'Categories listed successfully'
    ];
    }




    public function getFAQ($id)
    {

        $category=Category::whereId($id)->where('organization_id',Auth::user()->organization_id)->first();

        if($category){
            $qb = Category::whereId($id)->whereHas('FAQ',function($q){
                $q->orderBy('id','DESC');
            });
            $data = $qb->paginate(config('app.per_page'));
            return [
                'data' =>CategoryResource::collection($data)->response()->getData(true),
                'status' => true,
                'identifier_code' => 140001,
                'status_code' => 200,
                'message' => 'FAQS listed successfully'
        ];
        }
        else{
            return [
                'data' =>null,
                'status' => true,
                'identifier_code' => 140002,
                'status_code' => 400,
                'message' => 'this category does not exist'
        ];

        }

    }



    public function createCategory( $data){

        $category = Category::create( [
            'name'=> $data['name'],
           
            'organization_id'=> Auth::user()->organization_id,
            ]);
            return [
                'data' => new CategoryResource ($category),
                'status' => true,
                'identifier_code' => 217001,
                'status_code' => 200,
                'message' => 'Category created successfully'
            ];
          }


    public function createFAQ($data ,$categoryId){
        $category=Category::whereId($categoryId)
        ->whereOrganizationId(Auth::user()->organization_id)
        ->first();

        if($category){
            $FAQS=[];
            foreach ($data['FAQS'] as $FAQ) {

                 $result= FAQ::Create( [
                     'id'=>$FAQ['id'] ?? null,
                     'question' => $FAQ['question'],
                     'answer' => $FAQ['answer'],

                     'category_id' => $categoryId,

                 ]);
                 $FAQS[]=$result->load('category');

            }
             return [
                 'data' => new FAQWithCatResource ($FAQS),
                 'status' => true,
                 'identifier_code' => 198001,
                 'status_code' => 200,
                 'message' => 'FAQ created successfully'
             ];
        }
        else{
            return [
                'data' => null,
                'status' => false,
                'identifier_code' => 198002,
                'status_code' => 400,
                'message' => 'this category is not existed'
            ];
        }


      }


    public function updateFAQ($request,$id){

        $faq=FAQ::find($id);

        if(!$faq){
            return [
                'data' => null,
                'status' => true,
                'identifier_code' => 199002,
                'status_code' => 400,
                'message' => 'this FAQ not exist '
            ];
        }

        $org=Auth::user()->organization_id;
        $cat_org=$faq->category->organization_id;

        if($faq){
            if($cat_org != $org){
                return [
                    'data' => null,
                    'status' => true,
                    'identifier_code' => 199003,
                    'status_code' => 400,
                    'message' => 'this user is not the admin of this organization '
                ];

            }

            else{
                $faq->update([
                    'question' => $request->post('question'),
                    'answer' => $request->post('answer'),


                    ]);

                        return [
                            'data' => new FAQResource($faq),
                            'status' => true,
                            'identifier_code' => 199001,
                            'status_code' => 200,
                            'message' => 'FAQ updated successfully'
                        ];
                    }
        }

      }

      public function updateCategory($request,$id){

        $category=Category::find($id);

        if(!$category){
            return [
                'data' => null,
                'status' => true,
                'identifier_code' => 200002,
                'status_code' => 400,
                'message' => 'this Category not exist '
            ];
        }

        $org=Auth::user()->organization_id;
        $cat_org=$category->organization_id;

        if($category){
            if($cat_org != $org){
                return [
                    'data' => null,
                    'status' => true,
                    'identifier_code' => 200003,
                    'status_code' => 400,
                    'message' => 'this user is not the admin of this organization '
                ];

            }

            else{
                $category->update([
                    'name' => $request->post('name'),


                    ]);

                        return [
                            'data' => new CategoryResource($category),
                            'status' => true,
                            'identifier_code' => 200001,
                            'status_code' => 200,
                            'message' => 'Category updated successfully'
                        ];
                    }
        }



      }




    public function destroyCategory($id)
    {

        $category=Category::find($id);
        if(!$category){
            return [
                'data' =>null,
                'status' => true,
                'identifier_code' => 196002,
                'status_code' => 400,
                'message' => 'this category does not exist'
        ];
        }
        $org=Auth::user()->organization_id;
        $cat_org=$category->organization_id;

        if($category){
            if($cat_org != $org){
                return [
                    'data' => null,
                    'status' => true,
                    'identifier_code' => 196003,
                    'status_code' => 400,
                    'message' => 'this user is not the admin of this organization '
                ];
            }
        else{

            $category->delete();

            return [
                'data' =>null,
                'status' => true,
                'identifier_code' => 196001,
                'status_code' => 200,
                'message' => 'Category Deleted Successfully'
            ];
        }
    }

    }


    public function destroyQuestion($id)
    {
        $question=FAQ::find($id);
        if(!$question){
            return [
                'data' =>null,
                'status' => true,
                'identifier_code' => 197002,
                'status_code' => 400,
                'message' => 'this question does not exist'
        ];

        }

        else{

            $question->delete();
            return [
                'data' =>new FAQResource ($question),
                'status' => true,
                'identifier_code' => 197001,
                'status_code' => 200,
                'message' => 'Question Deleted Successfully'
            ];
        }


    }


}



?>
