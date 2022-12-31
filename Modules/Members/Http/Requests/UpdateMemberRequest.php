<?php

namespace Modules\Members\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Traits\ApiResponseTrait;
use Modules\Members\Models\Member;
use App\Models\User;

class UpdateMemberRequest extends FormRequest
{
    use  ApiResponseTrait;

    public function getUserId(){
        $user=User::whereHas('member',function($query){
            $query->where('id',$this->route('id'));
        })->first();
        return $user->id ??  null;
    }
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
            'email'      => 'email:rfc,dns|required|unique:users,email,'.$this->getUserId(),
            'team_id'    => 'required|exists:teams,id',
            'role'       => 'required|in:admin,staff member',
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

    protected function failedValidation(Validator $validator)
    {
        $this->apiResponseValidation($validator);
    }
}
