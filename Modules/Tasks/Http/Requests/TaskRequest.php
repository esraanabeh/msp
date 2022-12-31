<?php

namespace Modules\Tasks\Http\Requests;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{

    use  ApiResponseTrait;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description'                   => 'nullable|string',
            'name'                          => 'required|string',
            // 'due_date'                      => 'required|date|after:now',
            'automatic_reminder'            => 'required|boolean',
            'reminder_day'                  => ['nullable','required_if:automatic_reminder,true','gt:0'],
            'reminder_time'                 => ['nullable','required_if:automatic_reminder,true','date_format:H:i:s'],
            'shared_with_client'            => ['required','boolean'],
            'email_template'                => ['required_if:shared_with_client,true'],
            'email_template.title'          => 'nullable|string',
            'email_template.content'        => 'nullable|string',
            'tasks'                         => ['required','array','max:10'],
            'tasks.*'                       => ['required'],
            'tasks.*.title'                 => 'required|string',
            // 'tasks.*.answer'                => 'nullable|string',
            // 'tasks.*.is_completed'          => ['required','boolean'],
            'tasks.*.type'                  => 'required|in:text_block,short_replies,long_replies,multiple_choice,dorp_down,documents,date,single_choice',
            'tasks.*.description'           => 'nullable|string',
            'tasks.*.options'               => ['array', 'max:10' ,'required_if:tasks.*.type,multiple_choice,drop_down,single_choice'],
            'tasks.*.options.*.title'       => ['string' ,'required_if:tasks.*.type,multiple_choice,drop_down,single_choice','max:1024'],
            'tasks.*.options.*.is_selected' => ['boolean','required_if:tasks.*.type,multiple_choice,drop_down,single_choice'],
            'tasks.*.file'                  => ['required_if:tasks.*.type,documents','string'],
            'tasks.*.file_name'             => ['required_if:tasks.*.type,documents','string'],
            'team_id'                       => 'required|exists:teams,id',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

      /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $this->apiResponseValidation($validator);
    }
}
