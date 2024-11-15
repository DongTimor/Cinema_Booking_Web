@extends('layouts.customer')

@section('content')
    <h1 class="text-2xl text-center font-bold mb-6 mt-4">Top 20 Favorite Movies</h1>

    @if ($favoriteMovies->isEmpty())
        <p>No favorite movies found.</p>
    @else
        <div class="container grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">
            @foreach ($favoriteMovies as $movie)
                <div class="movie-card bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 ease-in-out mt-4 mx-2">
                    <h3 class="font-semibold text-lg mb-2">{{ $movie->movie_name }}</h3>
                    <p class="text-gray-700 mb-4">Total Tickets Sold: {{ $movie->total_tickets }}</p>
                    <a href="{{ route('favorite', $movie->movie_id) }}" class="text-blue-500 hover:underline">View Details</a>
                </div>
            @endforeach
        </div>
    @endif
@endsection
