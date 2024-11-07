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
        $action = $this->input('action');
        if ($action == 'create') {
            $rules = [
                'user_id' => 'required|exists:users,id',
                'seat_id' => 'required|exists:seats,id',
                'status' => 'required',
                'customer_id' => 'nullable|exists:customers,id',
                'showtime_id' => 'required|exists:showtimes,id',
                'schedule_id' => 'required|exists:schedules,id',
            ];
        } else if ($action == 'ticketConfirmationMail') {
            $rules['customer_id'] = 'required|exists:customers,id';
            $rules['movie_id'] = 'required|exists:movies,id';
            $rules['date'] = 'required|date';
            $rules['auditorium_id'] = 'required|exists:auditoriums,id';
            $rules['showtime_id'] = 'required|exists:showtimes,id';
            $rules['seats'] = 'required|array';
            $rules['cost'] = 'required|numeric';
            $rules['voucher_id'] = 'nullable|exists:vouchers,id';
            $rules['event_discount'] = 'nullable|numeric';
        }

        if ($this->isMethod('put')) {
            $rules = [
                'user_id' => 'sometimes|exists:users,id',
                'seat_id' => 'exists:seats,id',
                'customer_id' => 'sometimes|nullable',
                'showtime_id' => 'sometimes',
                'schedule_id' => 'sometimes',
            ];
        }



        return $rules;
    }
}
