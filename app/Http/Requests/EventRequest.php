<?php

namespace App\Http\Requests;

use App\Rules\ValidDate;
use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'title' => 'required',
            'start_date' => 'required',
            'end_date' => 'required|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'number_of_tickets' => 'numeric|nullable',
            'quantity' => 'numeric|nullable',
            'description' => 'string|min:1|max:1000|nullable',
            'all_day' => 'boolean',
            'all_movies' => 'boolean',
            'movies' => 'array|nullable',
        ];

        if (!$this->all_day) {
            $rules['start_time'] = 'required';
            $rules['end_time'] = 'required';
        }

        return $rules;
    }
}
