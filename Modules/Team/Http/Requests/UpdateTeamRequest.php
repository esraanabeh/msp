<?php

namespace Modules\Team\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Modules\Team\Rules\UniqueTeamName;
use Modules\Team\Models\Team;

class UpdateTeamRequest extends FormRequest
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
            'name' => ['required','string',new UniqueTeamName($this->route('id'))],
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
