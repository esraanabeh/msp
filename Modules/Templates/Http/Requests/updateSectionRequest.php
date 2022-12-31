<?php

namespace Modules\Templates\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class updateSectionRequest extends FormRequest
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
            'name'               => 'required|string',
            'description'        => 'required|string',
            'due_date'           => 'required|date|after:now',
            'reminder_day'       => 'required|integer',
            'reminder_time'      => 'required|date_format:H:i:s',
            'is_completed'       => 'boolean',
            'automatic_reminder' => 'required|boolean',
            'shared_with_client' => 'required|boolean',
            'team_id'            => 'required|exists:teams,id',
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
