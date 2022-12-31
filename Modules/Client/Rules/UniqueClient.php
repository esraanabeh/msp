<?php

namespace Modules\Client\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Client\Models\Client;
use Auth;

class UniqueClient implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $clientId;
    public function __construct($clientId = null)
    {
        $this->clientId = $clientId;
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
        if($this->clientId){
            $client = Client::where('email',$value)
                            ->where('organization_id',Auth::user()->organization_id)
                            ->whereNotIn('id',[$this->clientId])
                            ->first();
        } else {
            $client = Client::where('email',$value)
                            ->where('organization_id',Auth::user()->organization_id)
                            ->first();
        }
        if($client){
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
        return 'there exist client with the same email related to this organization';
    }
}
