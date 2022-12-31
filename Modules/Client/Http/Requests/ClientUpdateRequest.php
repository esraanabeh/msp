<?php

namespace Modules\Client\Http\Requests;

use App\Http\Traits\ApiResponseTrait;
use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Modules\Client\Rules\UniqueClient;

class ClientUpdateRequest extends FormRequest
{
    use ApiResponseTrait;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if(!is_null(request()->additional_questions))
        {
            return [
                'company_name' => ['required'],
                'contact_person' => ['required'],
                'industry' => ['required'],
                'phone_number' => ['required', new PhoneNumber],
                'email' => ['nullable' , 'email:rfc,dns' , new UniqueClient(request('clientId',null))],
                'address' => ['nullable'],
                'additional_questions' => ['nullable' , 'array','min:1'],
                'additional_questions.*.question' => ['required','string'],
                'additional_questions.*.answer' => ['required','string'],
                'number_of_employees' => ['nullable','numeric'],
            ];
        }else{
            return [
                'company_name' => ['required'],
                'contact_person' => ['required'],
                'phone_number' => ['required', new PhoneNumber],
                'email' => ['nullable' , 'email:rfc,dns' , new UniqueClient(request('clientId',null))],
                'address' => ['nullable'],
                'number_of_employees' => ['nullable','numeric'],
            ];
        }

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

    protected function failedValidation(Validator $validator)
    {
        $this->apiResponseValidation($validator);
    }
}
