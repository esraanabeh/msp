<?php

namespace Modules\Tasks\Http\Requests;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;


class UpdateClientTaskRequest extends FormRequest
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
            'tasks' => ['required' , 'array'],
            'tasks.*' => ['required'],
            'tasks.*.id' => ['required','exists:tasks_clients,id'],
            'tasks.*.answer' => ['nullable','string'],
            'tasks.*.file' => ['nullable'],
            'tasks.*.file_name' => ['required_with:tasks.*.file']
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
