<?php

namespace Modules\Industries\Repositories\Repos;
use Modules\Industries\Models\Industry;
use Carbon\Carbon;
use Modules\Industries\Repositories\Interfaces\IIndustryRepository;
use Illuminate\Support\Facades\Hash;
use Modules\Organization\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Modules\Industries\Http\Resources\IndustryResource;



class IndustryRepository implements IIndustryRepository{



    public function getIndustries()
    {
        $qb= Industry::whereHas('organization',function($query){
            $query->whereHas('organization_admin',function($query){
                $query->whereuserId(auth()->user()->id);
            });
        })->orderBy('id','DESC');
        $industries = $qb->paginate(config('app.per_page'));

         return [
            'data' => IndustryResource::collection($industries)->response()->getData(true),
            'status' => true,
            'identifier_code' => 129001,
            'status_code' => 200,
            'message' => 'successfully list industries'
        ];
    }

    public function createIndustry(  $request){
           $data= Industry::create( [
            'title' => $request->post( 'title' ),
            'organization_id' => Auth::user()->organization_id,
        ] );
        $industries = Industry::where('organization_id',Auth::user()->organization_id)->orderBy('id','DESC')->paginate(config('app.per_page'));
        return [
                    'data' =>  IndustryResource::collection($industries)->response()->getData(true),
                    'status' => true,
                    'identifier_code' => 128001,
                    'status_code' => 200,
                    'message' => 'industry  created successfully'
                ];

    }



    public function updateIndustry( $request){
        $org=auth()->user()->organization_admin;
        if ($org && $org->organization_id === (int) Auth::user()->organization_id){
        $indust=[];
        foreach($request->industries as $industry ){
        $data= Industry::updateOrCreate(
                ['id' => $industry['id'] ?? null] ,
                [  'title' => $industry['title'],
                'organization_id' => Auth::user()->organization_id
                ]
            );
            $indust[]=$data;
        }

        $industries = Industry::where('organization_id',Auth::user()->organization_id)->orderBy('id','DESC')->paginate(config('app.per_page'));
        return [
                    'data' =>  IndustryResource::collection($industries)->response()->getData(true),
                    'status' => true,
                    'identifier_code' => 130001,
                    'status_code' => 200,
                    'message' => 'industry  updated successfully'
                ];
    }
    else
    {
        return [
            'data' =>  'error',
            'status' => false,
            'identifier_code' => 130002,
            'status_code' => 400,
            'message' => 'this user is not oranization admin'
        ];
    }

    }

 public function deleteIndustry($id)
    {
        try{
            $industry=Industry::find($id);
            if(!is_null($industry))
            {
                $update_industry=$industry->delete();
                $industrys = Industry::where('organization_id',Auth::user()->organization_id)->paginate(config('app.per_page'));
                    return [
                        'data' => industryResource::collection($industrys)->response()->getData(),
                        'status' => true,
                        'identifier_code' => 131001,
                        'status_code' => 200,
                        'message' => 'industry deleted successfully'
                    ];
            }
            else
            {
                return [
                    'data' => NULL,
                    'status' => false,
                    'identifier_code' => 131002,
                    'status_code' => 400,
                    'message' => 'The industry is not exist'
                ];
            }

        }
        catch(\Exception $ex)
        {
            Log::info("Delete industry process failed due to : ".$ex->getMessage());
            return [
                'data' => NULL,
                'status' => false,
                'identifier_code' => 131003,
                'status_code' => 400,
                'message' => 'Some thing went wrong, plz try again later'
            ];
        }
    }




}



?>
