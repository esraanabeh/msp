<?php

namespace Modules\Quote\Repositories\Repos;

use Exception;
use DB;
use Auth;
use Modules\Client\Models\Client;
use Modules\Client\Models\ClientQuestion;
use Modules\Organization\Models\Organization;
use Modules\Question\Http\Resources\QuestionResource;
use Modules\Quote\Http\Resources\QuoteSectionResource;
use Modules\Quote\Http\Resources\QuoteTemplateResource;
use Modules\Quote\Models\ClientQuote;
use Modules\Quote\Models\QuoteSection;
use Modules\Quote\Models\QuoteTemplate;
use Modules\Quote\Repositories\Interfaces\IQuoteRepository;
use Modules\Services\Http\Resources\MasterAgreementResource;
use Modules\Services\Models\MasterServiceAgreement;
use Modules\Quote\Notifications\QouteNotification;
use Modules\Services\Http\Resources\ServiceResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Modules\Quote\Emails\QuotationMail;
use Modules\Quote\Emails\QuoteationMail;
use Modules\Quote\Http\Resources\ClientServiceResource;
use Modules\Quote\Models\QuotationLink;
use Illuminate\Support\Str;

class QuoteRepository implements IQuoteRepository
{

    public function createSection($data,$templateId)
    {
        $section = QuoteSection::create([
            'title' => $data['title'],
            'content' => $data['content'] ?? 'content',
            'quote_template_id' =>$templateId
        ]);

        $section->addMedia($data['file'])
                ->toMediaCollection();
        return [
            'data' => new QuoteSectionResource($section),
            'status' => true,
            'identifier_code' => 154001,
            'status_code' => 200,
            'message' => 'Quote template section created successfully'
        ];
    }

    public function updateTemplate($data,$templateId)
    {
        try{
            DB::beginTransaction();
            $quote = QuoteTemplate::find($templateId);
            $quote->update([
                        'introduction' => $data['introduction']
                    ]);

            if(key_exists('sections',$data)){
                foreach($data['sections'] as $section){
                    $newSection = QuoteSection::updateOrCreate(
                        ['id' => $section['id'] ?? null],[
                            // 'title' => $section['title'],
                            // 'content' => $section['content'] ?? null,
                            'order' => $section['order'],
                            // 'quote_template_id' => $templateId
                        ]
                        );

                        // if(count($newSection->getMedia()) == 0){
                        //     $newSection->addMedia($section['file'])
                        //         ->toMediaCollection();
                        // }
                }
            }

            DB::commit();

            return[
                'data' => new QuoteTemplateResource($quote->fresh()),
                'status' => true,
                'identifier_code' => 158001,
                'status_code' => 200,
                'message' => 'Quote template updated successfully'
            ];

        }catch(Exception $e){
            DB::rollback();
            return [
                'data'=>null,
                'status' => false,
                'identifier_code' => 158002,
                'status_code' => 400,
                'message' => $e->getMessage()
            ];
        }

    }

    public function getQuoteTemplate()
    {
        $user = Auth::user();
        $quote = QuoteTemplate::whereOrganizationId($user->organization_id)->first();
        if($quote){
            $quote['master_service_agreement'] = MasterServiceAgreement::where('organization_id',$user->organization_id)->where('isDefault',1)->first();
            return [
                'data'=>new QuoteTemplateResource($quote),
                'status' => true,
                'identifier_code' => 159001,
                'status_code' => 200,
                'message' => "quote template"
            ];
        } else {
            return [
                'data'=>null,
                'status' => false,
                'identifier_code' => 159002,
                'status_code' => 400,
                'message' => "quote template not found"
            ];
        }
    }

    public function generateQuote($clientId)
    {
        $client = Client::find($clientId);

        if($client){
            $validated = $this->checkQuoteTemplate($client->organization_id);
            if (!is_null($validated)) {
                return $validated;
            }
            $template = QuoteTemplate::where('organization_id',$client->organization_id)->first();
            $data = [];
            $data['client_information'] = [
                'contact_person' => $client->contact_person,
                'company_name' => $client->company_name,
                'phone_number' => $client->phone_number,
                'email' => $client->email
            ];

            $data['introduction'] = $template->introduction;

            $data['services'] = ClientServiceResource::collection($client->MRRServices->merge($client->ORRServices));

            $data['master_service_agreement'] = new MasterAgreementResource($client->organization->masterServiceAgreement()->where('isDefault',1)->first());

            $data['other_sections'] = QuoteSectionResource::collection($template->sections);

            return [
                'data'=>$data,
                'status' => true,
                'identifier_code' => 174001,
                'status_code' => 200,
                'message' => "quote generated"
            ];
        } else {
            return [
                'data'=>null,
                'status' => false,
                'identifier_code' => 174002,
                'status_code' => 400,
                'message' => "client not found"
            ];
        }
    }

    public function checkQuoteTemplate($organizationId)
    {
        $organization  = Organization::find($organizationId);
        if(!$organization->quoteTemplate || is_null($organization->quoteTemplate->introduction))
        {
            return [
                'data'=>null,
                'status' => false,
                'identifier_code' => 174003,
                'status_code' => 400,
                'message' => "the organization quote template is not completed !!"
            ];
        }
        elseif(count($organization->masterServiceAgreement) == 0 || !$organization->masterServiceAgreement()->where('isDefault',1)->exists()){
            return [
                'data'=>null,
                'status' => false,
                'identifier_code' => 174004,
                'status_code' => 400,
                'message' => "please select default master service agreement from your organization settings "
            ];
        }else{
            return null;
        }
    }

    public function saveClientQuote($clientId,$data)
    {
        if(!key_exists('id',$data)){
            $quote = ClientQuote::create([
                'introduction' => $data['introduction'],
                'services' => $data['services'],
                'master_service_agreement_id' => $data['master_service_agreement_id'],
                'other_sections' => $data['other_sections'] ?? [],
                'client_id' => $clientId,
                'organization_id' => Auth::user()->organization_id,
                'creator_user_id' => Auth::user()->id,
                'editor_user_id' => Auth::user()->id,
            ]);
        } else {
            $quote = ClientQuote::find($data['id']);
            if($quote->is_sent){
                $quote = ClientQuote::create([
                    'introduction' => $data['introduction'],
                    'services' => $data['services'],
                    'master_service_agreement_id' => $data['master_service_agreement_id'],
                    'other_sections' => $data['other_sections'] ?? [],
                    'client_id' => $clientId,
                    'organization_id' => Auth::user()->organization_id,
                    'creator_user_id' => Auth::user()->id,
                    'editor_user_id' => Auth::user()->id,
                ]);
            }else{
                $quote->update([
                    'introduction' => $data['introduction'],
                    'services' => $data['services'],
                    'master_service_agreement_id' => $data['master_service_agreement_id'],
                    'other_sections' => $data['other_sections'] ?? [],
                    'client_id' => $clientId,
                    'organization_id' => Auth::user()->organization_id,
                    'editor_user_id' => Auth::user()->id,
                ]);
            }
        }
        ClientQuestion::where('client_id',$clientId)
                        ->where('organization_id',Auth::user()->organization_id)
                        ->whereNull('client_quote_id')
                        ->update([
                           'client_quote_id' => $quote->id
                        ]);
        return [
            'data' =>$quote,
            'status' => true,
            'identifier_code' => 175001,
            'status_code' => 200,
            'message' => "client Quote saved successfully"
        ];
    }

    public function sendClientQuote($clientId)
    {
        $client = Client::find($clientId);
        if($client){
            $quote = ClientQuote::where('client_id',$clientId)->where('is_sent',0)->latest()->first();

            if($quote){
                $data=[];
                $data['client_information'] = [
                    'contact_person' => $client->contact_person,
                    'company_name' => $client->company_name,
                    'phone_number' => $client->phone_number,
                    'email' => $client->email
                ];
                $data['introduction'] = $quote->introduction;
                $data['services'] = $quote->services;
                $data['master_service_agreement'] = MasterServiceAgreement::withTrashed()->find($quote->master_service_agreement_id);
                $data['other_sections'] = QuoteSection::withTrashed()->find($quote->other_sections);
                $data['questions'] = $quote->questions;

                $pdf = Pdf::loadView('pdf.quote',['data'=>$data]);


                ob_end_clean();


                $pdf->save(storage_path().'\app\public\filename.pdf');

                $quote->addMedia(storage_path().'\app\public\filename.pdf')->toMediaCollection();

                File::delete(storage_path().'\app\public\filename.pdf');

                $code = $this->generateQuoteLink($client,$quote->id);

                Mail::send(new QuotationMail($quote,$client, $code, $data));
                $client->update([
                    'status' => 'Pending'
                ]);
                $quote->update(
                    ['is_sent' => 1]
                );

                return [
                    'data'=>null,
                    'status' => true,
                    'identifier_code' => 182001,
                    'status_code' => 200,
                    'message' => "client quote sent successfully"
                ];


            }else {
                $generatedQuote = $this->generateQuote($clientId);

                if(count($generatedQuote['data']['other_sections']) > 0){
                    $sections = $generatedQuote['data']['other_sections'] ->map(function($item){
                        return $item->id;
                    });
                }else {
                    $sections = [];
                }

                $quote = ClientQuote::create([
                    'introduction' => $generatedQuote['data']['introduction'],
                    'services' => $generatedQuote['data']['services'],
                    'master_service_agreement_id' => $generatedQuote['data']['master_service_agreement']->id,
                    'other_sections' => $sections,
                    'client_id' => $clientId,
                    'organization_id' => Auth::user()->organization_id,
                    'creator_user_id' => Auth::user()->id,
                    'editor_user_id' => Auth::user()->id,
                    'is_sent' => 1
                ]);

                ClientQuestion::where('client_id',$clientId)
                        ->where('organization_id',Auth::user()->organization_id)
                        ->whereNull('client_quote_id')
                        ->update([
                           'client_quote_id' => $quote->id
                        ]);


                $pdf = Pdf::loadView('pdf.quote',['data'=>$generatedQuote['data']]);

                ob_end_clean();


                $pdf->save(storage_path().'\app\public\filename.pdf');

                $quote->addMedia(storage_path().'\app\public\filename.pdf')->toMediaCollection();

                File::delete(storage_path().'\app\public\filename.pdf');

                $code = $this->generateQuoteLink($client,$quote->id);

                Mail::send(new QuotationMail($quote,$client, $code, $generatedQuote['data']));

                $client->update([
                    'status' => 'Pending'
                ]);

                return [
                    'data'=>null,
                    'status' => true,
                    'identifier_code' => 182001,
                    'status_code' => 200,
                    'message' => "client quote sent successfully"
                ];
            }
        }else {
            return [
                'data'=>null,
                'status' => false,
                'identifier_code' => 182002,
                'status_code' => 400,
                'message' => "client not found"
            ];
        }
    }

    public function getClientQuote($clientId)
    {
        $client = Client::find($clientId);

        if($client){
            $quote = ClientQuote::where('client_id',$clientId)->where('is_sent',0)->latest()->first();
            if($quote){
                $clientQuote = [];
                $clientQuote['client_information'] = [
                    'contact_person' => $client->contact_person,
                    'company_name' => $client->company_name,
                    'phone_number' => $client->phone_number,
                    'email' => $client->email
                ];
                $clientQuote['introduction'] = $quote->introduction;
                $clientQuote['services'] = $quote->services;
                $clientQuote['master_service_agreement'] = new MasterAgreementResource(MasterServiceAgreement::withTrashed()->find($quote->master_service_agreement_id));
                $clientQuote['other_sections'] = QuoteSectionResource::collection(QuoteSection::withTrashed()->find($quote->other_sections));
            } else {
                $clientQuote = $this->generateQuote($clientId)['data'];

            }

            return [
                'data'=>$clientQuote,
                'status' => true,
                'identifier_code' => 183001,
                'status_code' => 200,
                'message' => "client quote"
            ];
        }else {
            return [
                'data'=>null,
                'status' => false,
                'identifier_code' => 183002,
                'status_code' => 400,
                'message' => "client not found"
            ];
        }


    }

    public function generateQuoteLink($client,$quoteId)
    {
        $client->quoteLinks()->update([
            'valid' => 0
        ]);


        $code = Str::random(5).Carbon::now()->timestamp.Str::random(8);


        return QuotationLink::create([
                    'code' => $code,
                    'valid' => 1,
                    'status' => null,
                    'client_id' => $client->id,
                    'client_quote_id' => $quoteId
                ]);

    }

    public function checkQuotationLink($data)
    {
        $code = QuotationLink::where('code',$data['code'])->first();

        if($code){
            if($code->valid){
                return [
                    'data'=>$code,
                    'status' => true,
                    'identifier_code' => 222001,
                    'status_code' => 200,
                    'message' => "Valid Code"
                ];
            } else {
                return [
                    'data'=>null,
                    'status' => false,
                    'identifier_code' => 222003,
                    'status_code' => 400,
                    'message' => "Invalid Code"
                ];
            }
        } else {
            return [
                'data'=>null,
                'status' => false,
                'identifier_code' => 222002,
                'status_code' => 400,
                'message' => "Quotation Not Found"
            ];
        }
    }

    public function clientDecision($data)
    {
        $codeCheck = $this->checkQuotationLink($data);

        if($codeCheck['data']){
            $codeCheck['data']->update([
                'valid' => 0,
            ]);

            $codeCheck['data']->client()->update([
                'status' => $data['decision'] == 1 ? 'Active' : 'Declined'
            ]);

            $codeCheck['data']->quote()->update([
                'status' => $data['decision'] == 1 ? 'accepted' : 'rejected'
            ]);
        }

        return $codeCheck;
    }

}
