<?php

namespace Modules\Client\Http\Requests;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Auth;

class StoreMRRServiceRequest extends FormRequest
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
                                                        ->where('type','MRR')
                                                        ->where('organization_id',Auth::user()->organization_id)],
            'services.*.qty' => ['required','numeric','gt:0'],
            'services.*.is_opportunity' => ['required','boolean'],
            'services.*.opportunity_notes' => ['nullable']
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
