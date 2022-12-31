<?php

namespace Modules\Templates\Repositories\Repos;
use Modules\Templates\Models\Template;
use Modules\Templates\Models\Section;
use Carbon\Carbon;
use Modules\Templates\Repositories\Interfaces\ITemplateRepository;
use Illuminate\Support\Facades\Hash;
use Modules\Organization\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Team\Models\Team;
use Modules\Templates\Http\Resources\TemplateResource;
use Modules\Templates\Http\Resources\SectionResource;
use Modules\Templates\Http\Resources\SectionDetailsResource;
use Modules\Templates\Http\Resources\PublicTemplateResource;
use Modules\Templates\Http\Resources\MyTemplateResource;
use Modules\Templates\Models\ClientSection;
use Modules\Templates\Models\SectionEmailTemplate;
use Modules\Templates\Models\TemplateClient;


class TemplateRepository implements ITemplateRepository{


    public function createTemplate($data){
        try {
            // begin transaction
            DB::beginTransaction();

            $template = Template::create([
                'organization_id'=> Auth::user()->organization_admin->organization->id,
                'title'          => $data['title'],
                'description'    => $data['description'],
                'owner_id'       => $data['add_to_my_templates']==true ? Auth::user()->id:null ,
                'created_by'       => Auth::user()->id,
                ]);
            foreach($data['sections'] as $section )
            {
                $checkTeamExistence = Team::where("id",$section['team_id'])
                                            ->Where("organization_id",Auth::user()->organization_id)
                                            ->Where("status",1)
                                            ->first();
                if(!is_null($checkTeamExistence))
                {
                    $result=$template->sections()->create([
                        'name'               => $section['name'],
                        'team_id'            => $section['team_id'],
                        'shared_with_client' => $section['shared_with_client' ] == 'true' ? 1 : 0,
                    ]);

                    if($result->shared_with_client){
                        $result->emailTemplate()->create([
                            'title' => $section['name'],
                            'content' => config('app.default_introduction')
                        ]);
                    }
                }
                else
                {
                    // rollback!!!
                    DB::rollback();
                    return [
                        'data'            => NULL,
                        'status'          => true,
                        'identifier_code' => 166002,
                        'status_code'     => 400,
                        'message'         => 'Assigned team is not correct'
                    ];
                }
            }

            // Happy ending :)
            DB::commit();
            return [
                'data'            =>new TemplateResource ($template->fresh()),
                'status'          => true,
                'identifier_code' => 166001,
                'status_code'     => 200,
                'message'         => 'template created successfully'
            ];
        } catch (\Exception $e) {
            // rollback!!!
            DB::rollback();
            Log::info("create template issue : ".$e->getMessage());
            return [
                'data'            => NULL,
                'status'          => false,
                'identifier_code' => 166003,
                'status_code'     => 400,
                'message'         => 'Some thing went wrong, please try again later'
            ];
        }

    }


    public function listallTemplates()
    {
        $qb = Template::whereOrganizationId(Auth::user()->organization_id)
                       ->whereNull('owner_id')
        ->when(request()->has('search'),function($q){
            $q->where('title','LIKE', '%'.request('search').'%');

        })

        ->when(request()->has('sort'), function($q){
            if(request('sort') == 'Template Name'){
                $q->orderBy('title','ASC');
            }

        })

        ->when(request()->has('sort'), function($q){
            if(request('sort') == 'Oldest first'){
                $q->orderBy('created_at','ASC');
            }

        })

        ->when(request()->has('sort'), function($q){
            if(request('sort') == 'Newest first'){
                $q->orderBy('created_at','desc');
            }

        });

        $templates = $qb->paginate(config('app.per_page'));
        return [
            'data' => PublicTemplateResource::collection($templates)->response()->getData(true),
            'status' => true,
            'identifier_code' => 1622001,
            'status_code' => 200,
            'message' => 'templates'
        ];
    }


    public function listMyTemplates()
    {
        $qb = Template::whereOrganizationId(Auth::user()->organization_id)
                       ->where('owner_id',Auth::user()->id)
        ->when(request()->has('search'),function($q){
            $q->where('title','LIKE', '%'.request('search').'%');

        })

        ->when(request()->has('sort'), function($q){
            if(request('sort') == 'Template Name'){
                $q->orderBy('title','ASC');
            }

        })

        ->when(request()->has('sort'), function($q){
            if(request('sort') == 'Oldest first'){
                $q->orderBy('created_at','asc');
            }

        })

        ->when(request()->has('sort'), function($q){
            if(request('sort') == 'Newest first'){
                $q->orderBy('created_at','desc');
            }

        });

        $templates = $qb->paginate(config('app.per_page'));
        return [
            'data' => MyTemplateResource::collection($templates)->response()->getData(true),
            'status' => true,
            'identifier_code' => 167001,
            'status_code' => 200,
            'message' => 'my templates listed succefully'
        ];
    }




    public function listallSections($templateId)
    {

        $data = Section::wheretemplateId($templateId)->whereHas("template",function ($q){
            $q->where("organization_id",Auth::user()->organization_id)
                ->where(function ($q){
                    $q->whereNull("owner_id")
                    ->orWhere("owner_id",Auth::user()->id);
                });
        })->latest()->get();
         return [
            'data' =>SectionResource::collection($data),
            'status' => true,
            'identifier_code' => 140001,
            'status_code' => 200,
            'message' => 'sections listed successfully'
               ];
    }


    // Delete template section
    public function deleteSection($id)
    {
        // check section existance
        $section = Section::Where("id",$id)->whereHas("template",function ($q){
            $q->where("organization_id",Auth::user()->organization_id)
                ->where(function ($q){
                    $q->whereNull("owner_id")
                    ->orWhere("owner_id",Auth::user()->id);
                });
        })->first();

        // if section is not exist
        if(!$section){
            return [
                'data' => null,
                'status' => false,
                'identifier_code' => 171002,
                'status_code' => 400,
                'message' => 'this section is not exist'
            ];

        }
        // if section is exist
        else
        {
            // delete section
            $section->delete();
            return [
                'data' => new SectionResource($section),
                'status' => true,
                'identifier_code' => 143001,
                'status_code' => 200,
                'message' => 'section deleted successfully'
            ];
        }

    }

    // Delete template
    public function deleteTemplate($id)
    {
        // check template existence
        $template = Template::Where("id",$id)
                            ->where("organization_id",Auth::user()->organization_id)
                            ->first();

        // if template is not exist
        if(!$template){
            return [
                'data' => null,
                'status' => false,
                'identifier_code' => 219002,
                'status_code' => 400,
                'message' => 'this template is not exist'
            ];

        }
        // if template is exist
        else
        {
            // delete section
            $template->delete();
            return [
                'data' => new TemplateResource($template),
                'status' => true,
                'identifier_code' => 219001,
                'status_code' => 200,
                'message' => 'Template deleted successfully'
            ];
        }

    }

    // Update existing section
    public function updateSection($id ,  $request){
        // check section existance
        $section = Section::Where("id",$id)->whereHas("template",function ($q){
            $q->where("organization_id",Auth::user()->organization_id)
                ->where(function ($q){
                    $q->whereNull("owner_id")
                    ->orWhere("owner_id",Auth::user()->id);
                });
        })->first();

        // in case section not exist
        if(!$section)
        {
            return [
                'data' => null,
                'status' => false,
                'identifier_code' => 172002,
                'status_code' => 400,
                'message' => 'this section is not exist'
            ];
        }
        // in case section exist
        else
        {
            // check if assigned team is exist
            $checkTeamExistence = Team::where("id",$request->team_id)
                                            ->Where("organization_id",Auth::user()->organization_id)
                                            ->Where("status",1)
                                            ->first();
                // in case team exist
                if(!is_null($checkTeamExistence))
                {
                    $section->update([
                        'name'               => $request->name,
                        'description'        => $request->description,
                        'due_date'           => $request->due_date,
                        'reminder_day'       => $request->reminder_day,
                        'reminder_time'      => $request->reminder_time,
                        'team_id'            => $request->team_id,
                        'is_completed'       => $request->is_completed == 'true' ? 1 : 0,
                        'automatic_reminder' => $request->automatic_reminder == 'true' ? 1 : 0,
                        'shared_with_client' => $request->shared_with_client == 'true' ? 1 : 0,
                    ]);
                    return [
                        'data'            => new SectionDetailsResource($section->refresh()),
                        'status'          => true,
                        'identifier_code' => 107001,
                        'status_code'     => 200,
                        'message'         => 'section updated successfully'
                    ];
                }
                // in case team not exist
                else
                {
                    return [
                        'data' => null,
                        'status' => false,
                        'identifier_code' => 172003,
                        'status_code' => 400,
                        'message' => 'Assigned team is not exist'
                    ];
                }

        }


    }


    public function updateTemplate($id,$data){
        $template=Template::find($id);
        if(!$template){
            return [
                'data' => null,
                'status' => false,
                'identifier_code' => 173002,
                'status_code' => 400,
                'message' => 'this template is not exist'
            ];
        }
        else{
        $template ->update([
              'organization_id'=>Auth::user()->organization_admin->organization->id,
              'title' => $data['title'],
              'description' => $data['description'],
              'owner_id' => $data['add_to_my_templates']==true ? Auth::user()->id:null ,

              ]);
              if(key_exists('sections',$data)){
                foreach($data['sections'] as $section ){
                    $data=$template->sections()->updateOrCreate(['id' => $section['id'] ?? null] ,[
                          'name' => $section['name'],
                          'team_id' => $section['team_id'],
                          'shared_with_client' =>$section['shared_with_client'],
                      ]);
              }
          }
           return [
              'data' =>new TemplateResource($template),
              'status' => true,
              'identifier_code' => 173001,
              'status_code' => 200,
              'message' => 'template updated successfully'
          ];
        }

  }

    public function showTemplate($id)
    {
        $template = Template::whereOrganizationId(Auth::user()->organization_id)
                            ->whereId($id)
                            ->where(function ($q){
                                $q->whereNull("owner_id")
                                  ->orWhere("owner_id",Auth::user()->id);
                            })
                            ->first();
        if($template){
            return [
                'data' =>new TemplateResource($template),
                'status' => true,
                'identifier_code' => 193001,
                'status_code' => 200,
                'message' => 'template'
            ];
        } else {
            return [
                'data' =>null,
                'status' => false,
                'identifier_code' => 193002,
                'status_code' => 400,
                'message' => 'template is not exist'
            ];
        }
    }

    // show section details
    public function showSection($id)
    {
        // check section existence
        $section = Section::Where("id",$id)->whereHas("template",function ($q){
            $q->where("organization_id",Auth::user()->organization_id)
                ->where(function ($q){
                    $q->whereNull("owner_id")
                    ->orWhere("owner_id",Auth::user()->id);
                });
        })->first();

        // in case section exist
        if($section){
            return [
                'data'            => new SectionDetailsResource($section),
                'status'          => true,
                'identifier_code' => 204001,
                'status_code'     => 200,
                'message'         => 'template'
            ];
        }
        // in case section is not exist
        else
        {
            return [
                'data'            => null,
                'status'          => false,
                'identifier_code' => 204002,
                'status_code'     => 400,
                'message'         => 'section is not exist'
            ];
        }
    }

    // Update existing section
    public function addNewSection($request){
        // check template existence
        $template = Template::whereOrganizationId(Auth::user()->organization_id)
                            ->whereId($request->template_id)
                            ->where(function ($q){
                                $q->whereNull("owner_id")
                                    ->orWhere("owner_id",Auth::user()->id);
                            })
                            ->first();

        if(!$template)
        {
            return [
                'data' => null,
                'status' => false,
                'identifier_code' => 205002,
                'status_code' => 400,
                'message' => 'Template is not exist'
            ];
        }
        else
        {
             // check team existence
            $team = Team::whereOrganizationId(Auth::user()->organization_id)
                    ->whereId($request->team_id)
                    ->where("status","1")
                    ->first();

            if(!$team)
            {
                return [
                        'data' => null,
                        'status' => false,
                        'identifier_code' => 205002,
                        'status_code' => 400,
                        'message' => 'Template is not exist'
                    ];
            }
            else
            {
                try
                {
                    // begin transaction
                     DB::beginTransaction();

                    // save section
                    $section = new Section();
                    $section-> name               = $request->name;
                    $section-> team_id            = $request->team_id;
                    $section-> shared_with_client = $request->shared_with_client;
                    $section-> template_id        = $request->template_id;
                    $section->save();

                    // assign section to client
                    if($template->type == "custom")
                    {
                        $clientSection = New ClientSection();
                        $clientSection->section_id = $section->id;
                        $clientSection->client_id  = TemplateClient::where("template_id",$template->id)->first()->client_id;
                        $clientSection->save();
                    }

                    // Happy ending :)
                    DB::commit();
                    return [
                        'data'            => new SectionDetailsResource($section->refresh()),
                        'status'          => true,
                        'identifier_code' => 205001,
                        'status_code'     => 200,
                        'message'         => 'section updated successfully'
                    ];
                }
                catch (\Exception $e)
                {
                    // rollback!!!
                    DB::rollback();
                    Log::info("create template issue : ".$e->getMessage());
                    return [
                        'data'            => NULL,
                        'status'          => false,
                        'identifier_code' => 205002,
                        'status_code'     => 400,
                        'message'         => 'Some thing went wrong, please try again later'
                    ];
                }

            }
        }


    }

    // create template for client
    public function createClientTemplate($data ,$clientId){
        try {
            // begin transaction
            DB::beginTransaction();

            // save template
            $template = Template::create([
                    'organization_id'=>Auth::user()->organization_admin->organization->id,
                    'title'          => $data['title'],
                    'description'    => $data['description'],
                    'type'           => 'Custom',
                    'owner_id'       => null ,
                    ]);

            $client_template=TemplateClient::create([
                'client_id'  =>$clientId,
                'template_id'=> $template->id
            ]);
            // save sections
            foreach($data['sections'] as $section )
            {
                $checkTeamExistence = Team::where("id",$section['team_id'])
                                            ->Where("organization_id",Auth::user()->organization_id)
                                            ->Where("status",1)
                                            ->first();
                if(!is_null($checkTeamExistence))
                {
                    // create section
                    $result=$template->sections()->create([
                        'name'               => $section['name'],
                        'team_id'            => $section['team_id'],
                        'shared_with_client' => $section['shared_with_client' ] == 'true' ? 1 : 0,
                    ]);

                    if($result->shared_with_client){
                        $result->emailTemplate()->create([
                            'title' => $section['name'],
                            'content' => config('app.default_introduction')
                        ]);
                    }

                    // assign section to client
                    $clientSection = new ClientSection();
                    $clientSection->section_id = $result->id;
                    $clientSection->client_id  = $clientId;
                    $clientSection->due_date   = Carbon::now();
                    $clientSection->save();
                }
                else
                {
                    // rollback!!!
                    DB::rollback();
                    return [
                        'data'            => NULL,
                        'status'          => true,
                        'identifier_code' => 201002,
                        'status_code'     => 400,
                        'message'         => 'Assigned team is not correct'
                    ];
                }


            }
             // Happy ending :)
            DB::commit();
            return [
                'data' =>new TemplateResource($template),
                'status' => true,
                'identifier_code' => 201001,
                'status_code' => 200,
                'message' => 'client template created successfully'
            ];
        }
        catch (\Exception $e) {
            // rollback!!!
            DB::rollback();
            Log::info("create client template issue : ".$e->getMessage());
            return [
                'data'            => NULL,
                'status'          => false,
                'identifier_code' => 201003,
                'status_code'     => 400,
                'message'         => 'Some thing went wrong, please try again later'
            ];
        }
    }
}
?>
