<?php

namespace Modules\Industries\Http\Requests;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Industries\Models\Industry;
use Modules\Industries\Rules\UniqueTitle;

class UpdateIndustryRequest extends FormRequest
{

    use  ApiResponseTrait;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $industry = Industry::find($this->id);
        return [
            'industries' => 'required|array',
            'industries.*.title' =>  ['required' ,  new UniqueTitle(request('industryId',null))],

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
