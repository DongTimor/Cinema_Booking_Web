<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'seat_id' => 'required|exists:seats,id',
            'status' => 'required',
            'customer_id' => 'nullable|exists:customers,id',
            'showtime_id' => 'required|exists:showtimes,id',
            'price' => 'required',
            'schedule_id' => 'required|exists:schedules,id',
        ];
    }
}
