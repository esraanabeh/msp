<?php

namespace Modules\Industries\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Industries\Models\Industry;
use Auth;

class UniqueTitle implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $industryId;
    public function __construct($industryId = null)
    {
        $this->industryId = $industryId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($this->industryId){
            $industry = Industry::where('title',$value)
                            ->where('organization_id',Auth::user()->organization_id)
                            ->whereNotIn('id',[$this->industryId])
                            ->first();
        } else {
            $industry = Industry::where('title',$value)
                            ->where('organization_id',Auth::user()->organization_id)
                            ->first();
        }
        if($industry){
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This Industry already exist ';
    }
}
