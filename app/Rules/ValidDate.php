<?php

namespace App\Rules;

use App\Models\Event;
use App\Models\Movie;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidDate implements ValidationRule
{
    protected $movieId;
    public function __construct($movieId)
    {
        $this->movieId = $movieId;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->passes($attribute, $value)) {
            $fail($this->message());
        }
    }

    public function passes($attribute, $value)
    {
        try {
            $date = Carbon::createFromFormat('m/d/Y', $value);

            $movie = Movie::find($this->movieId);

            return $movie && $date->lessThanOrEqualTo(Carbon::parse($movie->end_date));
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     */
    public function message()
    {
        return 'The :attribute field must be less than or equal to the movie\'s end date.';
    }
}
