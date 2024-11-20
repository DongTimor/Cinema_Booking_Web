@extends('layouts.customer')

@section('content')
    <h1 class="text-2xl text-center font-bold mb-6 mt-4">Top 20 Favorite Movies</h1>

    @if ($favoriteMovies->isEmpty())
        <p>No favorite movies found.</p>
    @else
        <div class="mt-4 container grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($favoriteMovies as $movie)
                <div class="movie-card bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 ease-in-out">
                    <img src="{{ $movie['image_url'] }}" alt="{{ $movie['name'] }}" class="w-full h-48 object-cover rounded-md mb-4">
                    <h3 class="font-semibold text-lg mb-2" title="{{ $movie->movie_name }}">
                        {{ \Illuminate\Support\Str::limit($movie->movie_name, 20, '...') }}
                    </h3>
                    <p class="text-gray-700">Original Price: {{ $movie->price }}</p>
                    <p class="text-gray-700">Total Tickets Sold: {{ $movie->total_tickets }}</p>
                    <a href="{{ route('detail', ['id' => $movie['movie_id']]) }}" class="mt-4 inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-center">
                        See Now
                    </a>
                </div>
            @endforeach
        </div>
    @endif
@endsection
