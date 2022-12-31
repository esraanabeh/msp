<?php

namespace Modules\Quote\Http\Requests;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;


class ClientQuoteRequest extends FormRequest
{
    use ApiResponseTrait;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'introduction' => ['required','string'],
            'services' => ['required' , 'array'],
            'services.*' => ['required'],
            'services.*.title' => ['required','string'],
            'services.*.unit_cost' => ['required', 'numeric'],
            'services.*.total_amount' => ['required','numeric','gt:0'],
            'services.*.qty' => ['required','numeric','gt:0'],
            'services.*.type' => ['required','in:MRR,ORR'],
            'master_service_agreement_id' => ['required'],
            'other_sections' =>['nullable','array']
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

    protected function failedValidation(Validator $validator)
    {
        $this->apiResponseValidation($validator);
    }
}
