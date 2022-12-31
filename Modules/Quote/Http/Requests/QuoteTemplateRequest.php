<?php

namespace Modules\Quote\Http\Requests;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;


class QuoteTemplateRequest extends FormRequest
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
            'introduction' => 'required',
            'sections' => ['nullable','array'],
            'sections.*' => ['required'],
            'sections.*.order' => ['required'],
            'sections.*.file' => ['nullable','file'],
            'sections.*.title' => ['nullable','string'],
            'sections.*.content' => ['nullable','string']
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
