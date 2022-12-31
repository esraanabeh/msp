<?php

namespace Modules\Organization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;



class OrganizatonRequest extends FormRequest
{

    use  ApiResponseTrait;
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                => ['required','string'],
            'team_members'        => ['required','string'],
            // 'usage_system_target' => ['nullable','string'],
            'main_speciality'     => ['required','string'],
        ];
    }

    public function messages()
{
    return [
        'name.required' => 'Please enter the Organization name',
        'team_members.required' => 'Please select the number of your team',
        // 'usage_system_target.required' => 'Please select the purpose of using our software',
        'main_speciality.required' => 'Please select your role'
    ];
}




    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $this->apiResponseValidation($validator);
    }
}
