@foreach ($showtimes as $showtime)
    <button type="button" onclick="fetchSeats('{{ $date }}',{{ $movieId }},{{ $showtime->id }}, this)"
        class="showtime-btn {{ $showtime->tickets->count() == $orderedCount ? "bg-gray-300 pe-none" : "" }} rounded border border-gray-300 px-2 py-2 hover:bg-gray-300"
        data-bs-toggle="modal" data-bs-target="#seats-modal">
        {{ \Carbon\Carbon::parse($showtime->start_time)->format("H:i") }}
    </button>
@endforeach
