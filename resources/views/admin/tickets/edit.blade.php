@extends('layouts.admin')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/tickets/edit.css') }}">
@endsection
@section('content')
    <form action="{{ route('tickets.update', $ticket) }}" method="post">
        @csrf
        @method('PUT')

        <h2>Edit ticket : {{ $ticket->id }}</h2>

        <x-adminlte-select id="movie_id" name="movie_id" label="Movie">
            @foreach ($movies as $movie)
                @if ($movie->id === $ticket->schedule->movie_id)
                    <option selected value="{{ $movie->id }}">{{ $movie->name }}</option>
                @else
                    <option value="{{ $movie->id }}">{{ $movie->name }}</option>
                @endif
            @endforeach
        </x-adminlte-select>

        <x-adminlte-select id="user_id" name="user_id" label="Seller">
            @foreach ($users as $user)
                @if ($user->id === $ticket->user->id)
                    <option selected value="{{ $user->id }}">{{ $user->name }}</option>
                @else
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endif
            @endforeach
        </x-adminlte-select>

        <x-adminlte-select id="customer_id" name="customer_id" label="Customer">
            @foreach ($customers as $customer)
                @if ($customer->id === $ticket->customer_id)
                    <option selected value="{{ $customer->id }}">{{ $customer->name }}</option>
                @else
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endif
            @endforeach
        </x-adminlte-select>

        <p class="mt-5" style="font-size: 1rem; font-weight: bold;">Filter by :</p>
        <div class="row ml-3">

            <div class="col-md-1">
                <input type="checkbox" id="date-filter" name="group" value="1">
                <label for="date-filter">Date</label><br>
            </div>

            <div class="col-md-1">
                <input type="checkbox" id="auditorium-filter" name="group" value="2">
                <label for="auditorium-filter">Auditorium</label><br>
            </div>

            <div class="col-md-1">
                <input type="checkbox" id="showtime-filter" name="group" value="3">
                <label for="showtime-filter">Showtime</label>

            </div>
        </div>

            <x-adminlte-select id="date" name="date" label="Date">
                <option selected value="{{ $ticket->schedule->date }}">{{ $ticket->schedule->date }}</option>
            </x-adminlte-select>

            <x-adminlte-select id="showtime_id" name="showtime_id" label="Showtime" disabled>
                <option selected value="{{ $ticket->showtime_id }}">{{ $ticket->showtime->id }}</option>
            </x-adminlte-select>

            <x-adminlte-select id="auditorium_id" name="auditorium_id" label="Auditorium" disabled>
                <option selected value="{{ $ticket->schedule->auditorium_id }}">{{ $ticket->schedule->auditorium->name }}
                </option>
            </x-adminlte-select>

            {{-- <x-adminlte-select id="seat_id" name="seat_id" label="Seat" disabled>
                <option selected value="{{ $ticket->seat_id }}">{{ $ticket->seat->seat_number }}</option>
            </x-adminlte-select> --}}

            <div class="seats_container_lable">
                ----------Seats's Booking----------
            </div>
            <div id="seats_container" class="seats_container">
            </div>
            <div id="another_seats_container_lable" class="seats_container_lable"
                style="display: none !important;">
                ----------Another Seats's Booking----------
            </div>
            <div id="another_seats_container" class="anotherseats_container">
            </div>
            <button type="button" onclick="click()">Update</button>
            {{-- <x-adminlte-button label="Update" type="button" onclick="click()" /> --}}

    </form>
@endsection

@section('scripts')
    <script>
        const ticket = @json($ticket);
        const date = @json($ticket->schedule->date);
        const showtime_id = @json($ticket->showtime_id);
        const showtime_value = @json(
            \Carbon\Carbon::parse($ticket->showtime->start_time)->format('H:i') .
                ' - ' .
                \Carbon\Carbon::parse($ticket->showtime->end_time)->format('H:i'));
        const auditorium_id = @json($ticket->schedule->auditorium_id);
        const auditorium_name = @json($ticket->schedule->auditorium->name);
    </script>
    <script src="{{ asset('js/tickets/edit.js') }}"></script>
@endsection
