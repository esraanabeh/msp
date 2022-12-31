<?php

namespace Modules\Members\Http\Requests;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class StoreMemberRequest extends FormRequest
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
            'first_name' => 'required|regex:/(^[a-zA-Z ]*$)/',
            'last_name'  => 'required|regex:/(^[a-zA-Z ]*$)/',
            'email'      => 'email:rfc,dns|required|unique:users',
            'team_id'    => 'required|exists:teams,id',
            'role'       => 'required|in:admin,staff member',
            'status'     => 'required'
        ];
    }



    public function messages()
    {
        return [
            'first_name.required' => "first Name is required",
            'last_name.required' => "last Name is required",
            'email.required' => "email is required",
            'first_name.regex' => "first Name does not accept numbers or symbols ",
            'last_name.regex' => "last Name does not accept numbers or symbols",
            // 'email.unique' => "email must be unique",
            'role.required' => "role is required",
            'team_id.required' => "organization team is required",

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
