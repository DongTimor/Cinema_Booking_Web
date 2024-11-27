<option value="">-Select Showtime-</option>
@foreach ($auditoriums as $auditorium)
    <option value="{{ $auditorium->id }}">
        {{ $auditorium->name }}
    </option>
@endforeach
