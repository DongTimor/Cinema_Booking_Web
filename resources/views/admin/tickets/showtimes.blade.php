<option value="">-Select Showtime-</option>
@foreach ($showtimes as $showtime)
    <option value="{{ $showtime->id }}">
        {{ \Carbon\Carbon::parse($showtime->start_time)->format("H:i") }} -
        {{ \Carbon\Carbon::parse($showtime->end_time)->format("H:i") }}
    </option>
@endforeach
