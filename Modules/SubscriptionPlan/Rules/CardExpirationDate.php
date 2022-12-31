<?php

namespace Modules\SubscriptionPlan\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class CardExpirationDate implements Rule
{
    protected $message;

    /**
     * Date field format.
     *
     * @var string
     */
    protected $format;

    /**
     * CardExpirationDate constructor.
     *
     * @param  string  $format  Date format
     */
    public function __construct(string $format)
    {
        $this->message = "Invalid Card Expiration Date";
        $this->format = $format;
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
        try {
            // This can throw Invalid Date Exception if format is not supported.
            // Carbon::parse($value);
            $myArray = explode('/', $value);
            $date = Carbon::createFromFormat($this->format, $myArray[0].$myArray[1]);
            return (new ExpirationDateValidator($date->year, $date->month))
                ->isValid();
        } catch (\InvalidArgumentException $ex) {
            $this->message = "Invalid Card Expiration Date";
            return false;
        } catch (\Exception $ex) {
            $this->message = "Invalid Card Expiration Date";
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans($this->message);
    }
}