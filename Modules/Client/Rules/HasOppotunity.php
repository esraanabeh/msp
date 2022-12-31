<?php

namespace Modules\Client\Rules;

use Illuminate\Contracts\Validation\Rule;

class HasOppotunity implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach($value as $question)
        {
            if($question['is_opportunity'] == true){
                return true;
            }
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'you have to add at least one question as an opportunity';
    }
}
