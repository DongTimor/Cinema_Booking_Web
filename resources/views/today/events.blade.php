@extends('layouts.customer')

@section('content')
    <h1 class="text-2xl font-bold mb-6 text-center mt-4">Ongoing Events</h1>

    @if ($events->isEmpty())
        <p>No events are currently ongoing.</p>
    @else
        <div class="mt-4 container grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($events as $event)
                <div class="event-card bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 ease-in-out mx-2 mt-2">
                    <h3 class="font-semibold text-lg mb-2">{{ $event->title }}</h3>
                    <p class="text-gray-700 mb-4">{{ $event->description }}</p>
                    <p class="text-gray-600">Start Time: {{ \Carbon\Carbon::parse($event->start_time)->format('H:i ~ d-m-Y') }}</p>
                    <p class="text-gray-600">End Time: {{ \Carbon\Carbon::parse($event->end_time)->format('H:i ~ d-m-Y') }}</p>
                    <p class="text-xl font-semibold text-green-600">Discount: {{ $event->discount_percentage }}%</p>
                </div>
            @endforeach
        </div>
    @endif
@endsection
