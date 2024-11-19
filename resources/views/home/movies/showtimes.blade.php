@foreach ($showtimes as $showtime)
    <button type="button" onclick="fetchSeats('{{ $date }}',{{ $movie_id }},{{ $showtime->id }}, this)"
        class="showtime-btn {{ $showtime->count == $showtime->seats ? "bg-gray-300 pe-none" : "" }} rounded border border-gray-300 px-2 py-2 hover:bg-gray-300"
        id="{{ $showtime->id }}" start-time="{{ $showtime->start_time }}" end-time="{{ $showtime->end_time }}"
        data-bs-toggle="modal" data-bs-target="#seats-modal">
        {{ \Carbon\Carbon::parse($showtime->start_time)->format("H:i") }}
    </button>
@endforeach
