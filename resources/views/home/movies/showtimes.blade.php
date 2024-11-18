@foreach ($showtimes as $showtime)
    <button 
        type="button"
        id="{{$showtime->id}}"
        start-time="{{$showtime->start_time}}"
        end-time="{{$showtime->end_time}}"
        {{$showtime->is_full || $showtime->is_past ? 'disabled ' : ''}} 
        onclick="fetchSeats('{{ $date }}',{{ $movieId }},{{ $showtime->id }}, this)"
        class="showtime-btn rounded border border-gray-300 px-2 py-2  {{$showtime->is_full || $showtime->is_past ? 'bg-gray-600' : 'hover:bg-gray-300'}}" 
        data-bs-toggle="modal"
        data-bs-target="#seats-modal">
        {{ \Carbon\Carbon::parse($showtime->start_time)->format("H:i") }}
    </button>
@endforeach
