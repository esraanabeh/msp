<?php

namespace Modules\Question\Repositories\Repos;

use Modules\Question\Models\Question;
use Auth;
use Modules\Services\Models\Service;
use Modules\Client\Models\Client;
use Modules\Client\Models\ClientQuestion;
use Modules\Question\Http\Resources\QuestionResource;
use Modules\Services\Http\Resources\ServiceResource;

class QuestionRepository
{

    public function listQuestions()
    {
        $qb = Question::whereOrganizationId(Auth::user()->organization_id)->orderBy('id','DESC');

        return [
            'data'            => QuestionResource::collection($qb->paginate(config('app.per_page')))->response()->getData(true),
            'status'          => true,
            'identifier_code' => 152001,
            'status_code'     => 200,
            'message'         => 'list client quote questions'
        ];
    }

    // moved to services module

    public function findOrCreateQuestion($data)
    {
        $question = Question::firstOrCreate(
                ['id' => $data['id'] ?? null] , [
                    'question'        => $data['question'] ?? null,
                    'type'            => $data['type'] ?? null,
                    'service_id'      => $data['service_id'] ?? null,
                    'client_id'       => $data['client_id'] ?? null,
                    'organization_id' => Auth::user()->organization_id
                ]
            );

        return $question;
    }

    public function updateOrCreateClientQuestion($clientId,$question,$data)
    {
        $question = ClientQuestion::updateOrCreate(['client_id'=>$clientId , 'question_id' => $question->id ?? null],[
            'is_opportunity'    => $data['is_opportunity'],
            'opportunity_notes' => $data['opportunity_notes'] ?? null,
            'service_cost'      => $data['service_cost'] ?? null,
            'answer'            => $data['answer'] ?? null,
            'client_id'         => $clientId,
            'question_id'       => $question->id
        ]);

        return $question;
    }
}
