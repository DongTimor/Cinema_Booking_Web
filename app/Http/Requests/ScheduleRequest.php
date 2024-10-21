<?php

namespace App\Http\Requests;

use App\Rules\ValidDate;
use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'movie_id' => 'required|exists:movies,id',
            'date' =>  ['required', new ValidDate],
            'auditorium_id' => 'required|exists:auditoriums,id',
            'showtime_id' => 'required|exists:showtimes,id',
        ];
        return $rules;
    }
}
