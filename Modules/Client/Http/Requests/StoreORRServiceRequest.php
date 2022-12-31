<?php

namespace Modules\Client\Http\Requests;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Auth;
use Illuminate\Validation\Rule;

class StoreORRServiceRequest extends FormRequest
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
            'services' => ['required','array'],
            'services.*' => ['required'],
            'services.*.service_id' => ['required',Rule::exists('services','id')
                                                        ->where('type','ORR')
                                                        ->where('organization_id',Auth::user()->organization_id)],
            'services.*.cost'=> ['required','numeric','gt:0'],
            'services.*.qty' => ['required','numeric','gt:0'],
            'services.*.is_opportunity' => ['required','boolean'],
            'services.*.opportunity_notes' => ['nullable','string'],
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
