@extends('layouts.customer')

@section('content')
    <h1 class="text-2xl text-center font-bold mb-6 mt-4">Discounted Movies</h1>

    @if ($discountMovies->isEmpty())
        <p class="text-center mt-4">No movies are currently discounted.</p>
    @else
        <div class="container mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($discountMovies as $movie)
                <div class="movie-card bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 ease-in-out">
                    <img src="{{ $movie['image_url'] }}" alt="{{ $movie['name'] }}" class="w-full h-48 object-cover rounded-md mb-4">
                    <h3 class="font-semibold text-lg mb-2">{{ $movie['name'] }}</h3>
                    <p class="text-gray-700">Original Price: <span class="line-through text-red-500">{{ $movie['price'] }}</span></p>
                    <p class="text-gray-700">Discount: {{ $movie['discount_percentage'] }}%</p>
                    <p class="text-gray-600">Valid from: {{ \Carbon\Carbon::parse($movie['start_date'])->format('d-m-Y') }} to {{ \Carbon\Carbon::parse($movie['end_date'])->format('d-m-Y') }}</p>
                    <p class="text-xl font-semibold text-green-600">Discounted Price: {{ $movie['discounted_price'] }}VNĐ</p>
                </div>
            @endforeach
        </div>
    @endif
@endsection
