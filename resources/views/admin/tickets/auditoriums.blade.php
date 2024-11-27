<option value="">-Select Auditorium-</option>
@foreach ($auditoriums as $auditorium)
    <option value="{{ $auditorium->id }}">
        {{ $auditorium->name }}
    </option>
@endforeach
