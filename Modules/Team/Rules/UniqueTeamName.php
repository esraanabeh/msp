<?php

namespace Modules\Team\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Team\Models\Team;
use Auth;

class UniqueTeamName implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $teamId;
    public function __construct($teamId)
    {

        $this->teamId = $teamId;
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
        $team = Team::where('id','!=',$this->teamId)->where('organization_id',Auth::user()->organization_id)->where('name',$value)->first();
        if($team){
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
        return 'This team name already exist ... Please enter different team name';
    }
}
