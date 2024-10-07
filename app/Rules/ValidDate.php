<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidDate implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }
    public function passes($attribute, $value)
    {
        try {
            Carbon::createFromFormat('d/m/Y', $value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     */
    public function message()
    {
        return 'The :attribute field must be a valid date in the format dd/mm/yyyy.';
    }
}
