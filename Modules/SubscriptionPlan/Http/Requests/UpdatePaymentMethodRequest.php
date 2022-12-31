<?php

namespace Modules\SubscriptionPlan\Http\Requests;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Modules\SubscriptionPlan\Rules\CardExpirationDate;

class UpdatePaymentMethodRequest extends FormRequest
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
            'name'            => ['required','string'],
            'card_id'         => ['required','string'],
            'address_zip'     => ['required','regex:/\b\d{5}\b/'],
            'expiration_date' => ['required',new CardExpirationDate('my')]
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
