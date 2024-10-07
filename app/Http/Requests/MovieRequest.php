<?php

namespace App\Http\Requests;

use App\Rules\ValidDate;
use Illuminate\Foundation\Http\FormRequest;

class MovieRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255|unique:movies,name',
            'duration' => 'required|integer',
            'description' => 'string',
            'start_date' => ['required', new ValidDate],
            'end_date' => ['required', new ValidDate],
            'category_id' => 'array',
            'trailer' => 'required|url',
            'image_id' => 'array',
        ];
    }
}
