@extends('layouts.admin')
{{-- {{ dd($ticket->all()) }} --}}
@section('content')
    <form action="{{ route('tickets.update', $ticket) }}" method="post">
        @csrf
        @method('PUT')

        <h2>Edit ticket : {{ $ticket->id }}</h2>

        <label for="movie">Movie</label>
        <x-adminlte-select name="movie_id">
            @foreach ($movies as $movie)
                @if ($movie->id === $ticket->showtime->movie->id)
                    <option selected value="{{ $movie->id }}">{{ $movie->name }}</option>
                @else
                    <option value="{{ $movie->id }}">{{ $movie->name }}</option>
                @endif
            @endforeach
        </x-adminlte-select>

        <label for="user_id">Seller</label>
        <x-adminlte-select name="user_id">
            @foreach ($users as $user)
                @if ($user->id === $ticket->user->id)
                    <option selected value="{{ $user->id }}">{{ $user->name }}</option>
                @else
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endif
            @endforeach
        </x-adminlte-select>

        <label for="customer_id">Customer</label>
        <x-adminlte-select name="customer_id">
            @foreach ($customers as $customer)
                @if ($customer->id === $ticket->customer->id)
                    <option selected value="{{ $customer->id }}">{{ $customer->name }}</option>
                @else
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endif
            @endforeach
        </x-adminlte-select>

        <label for="showtime_id">Showtime</label>
        <x-adminlte-select name="showtime_id">
            @foreach ($showtimes as $showtime)
                @if ($showtime->id === $ticket->showtime->id)
                    <option selected value="{{ $showtime->id }}">{{ $showtime->id }}</option>
                @else
                    <option value="{{ $showtime->id }}">{{ $showtime->id }}</option>
                @endif
            @endforeach
        </x-adminlte-select>

        <label for="seat_id">Seat</label>
        <x-adminlte-select name="seat_id">
            @foreach ($seats as $seat)
                @if ($seat->id === $ticket->seat->id)
                    <option selected value="{{ $seat->id }}">{{ $seat->seat_number }}</option>
                @else
                    <option value="{{ $seat->id }}">{{ $seat->seat_number }}</option>
                @endif
            @endforeach
        </x-adminlte-select>
        <x-adminlte-button label="Update" type="submit" />

    </form>
@endsection

@section('script')
    <script src="{{ asset('js/ticket/ticket-edit.js') }}"></script>
@endsection
