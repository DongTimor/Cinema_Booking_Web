@extends('layouts.admin')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/tickets/edit.css') }}">
@endsection
@section('content')
    <h2>Edit ticket : {{ $ticket->id }}</h2>
    <x-adminlte-select id="movie_id" name="movie_id" label="Movie" disabled>
        @foreach ($movies as $movie)
            @if ($movie->id === $ticket->schedule->movie_id)
                <option selected value="{{ $movie->id }}">{{ $movie->name }}</option>
            @else
                <option value="{{ $movie->id }}">{{ $movie->name }}</option>
            @endif
        @endforeach
    </x-adminlte-select>
    <x-adminlte-select id="user_id" name="user_id" label="Seller" disabled>
        @foreach ($users as $user)
            @if ($user->id === $ticket->user->id)
                <option selected value="{{ $user->id }}">{{ $user->name }}</option>
            @else
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endif
        @endforeach
    </x-adminlte-select>
    <x-adminlte-select id="customer_id" name="customer_id" label="Customer">
        <option value="">Null</option>
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
    <div id="select-container" class="d-flex flex-column">
        <div id="date-container">
            <x-adminlte-select id="date" name="date" label="Date">
                <option selected value="{{ $ticket->schedule->date }}">{{ $ticket->schedule->date }}</option>
            </x-adminlte-select>
        </div>
        <div id="showtime-container">
            <x-adminlte-select id="showtime_id" name="showtime_id" label="Showtime" disabled>
                <option selected value="{{ $ticket->showtime_id }}">{{ $ticket->showtime->id }}</option>
            </x-adminlte-select>
        </div>
        <div id="auditorium-container">
            <x-adminlte-select id="auditorium_id" name="auditorium_id" label="Auditorium" disabled>
                <option selected value="{{ $ticket->schedule->auditorium_id }}">
                    {{ $ticket->schedule->auditorium->name }}
                </option>
            </x-adminlte-select>
        </div>
    </div>
    <div class="seats_container_lable">
        ----------Seats's Booking----------
    </div>
    <div id="description" class="description">
        <div id="current-seat-container">
            <x-adminlte-input id="seat_number" name="seat_number" label="Seat Number" disabled
                value="{{ $ticket->seat->seat_number }}" />
            <x-adminlte-select id="seat_status" name="seat_status" label="Status">
                @if ($ticket->status == 'ordered')
                    <option selected value="ordered">Ordered</option>
                    <option value="settled">Settled</option>
                @elseif ($ticket->status == 'settled')
                    <option selected value="settled">Settled</option>
                    <option value="ordered">Ordered</option>
                @endif
            </x-adminlte-select>
        </div>
        <div class="seats_container_description_container">
            <div class="seats_container_description_lable">
                --Seats's Description--
            </div>
            <div id="seats_container_description" class="seats_container_description">
                <div class="description-row">
                    <span class="span-1"></span>
                    <div>Empty</div>
                </div>
                <div class="description-row">
                    <span class="span-2"></span>
                    <div>Unplaced</div>
                </div>
                <div class="description-row">
                    <span class="span-3"></span>
                    <div>Ordered</div>
                </div>
                <div class="description-row">
                    <span class="span-4"></span>
                    <div>Settled</div>
                </div>
                <div class="description-row">
                    <span class="span-5"></span>
                    <div>Selected</div>
                </div>
            </div>
        </div>
    </div>
    <span id="guide">[Select a seat to change current seat of this ticket, if you unplaced it, ticket will be change
        to old seat]</span>
    <div id="seats_container" class="seats_container">
    </div>
    <div id="another_seats_container_lable" class="seats_container_lable" style="display: none !important;">
        ----------Another Seats's Booking----------
    </div>
    <div id="another_seats_container" class="anotherseats_container">
    </div>
    <div class="modal fade" id="Select-Status-Modal" tabindex="-1" role="dialog"
        aria-labelledby="Select-Status-ModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="Select-Status-ModalLabel">Select Status</h5>
                    <x-adminlte-button icon="fas fa-times" id="deleteButton" theme="danger"
                        onclick="closeSelectStatusModal()" />
                </div>
                <div class="modal-body">
                    <x-adminlte-select id="status" name="status" label="Status" required>
                        <option value="unplaced">Unplaced</option>
                        <option value="ordered" {{ $ticket->seat->status == 'ordered' ? 'selected' : '' }}>Ordered
                        </option>
                        <option value="settled" {{ $ticket->seat->status == 'settled' ? 'selected' : '' }}>Settled
                        </option>
                    </x-adminlte-select>
                </div>
                <div class="modal-footer">
                    <x-adminlte-button id="apply-status-button" label="Apply" theme="success"
                        onclick="applyStatus()" />
                </div>
            </div>
        </div>
    </div>
    <div class="update-button-container">
        <x-adminlte-button style="width: 200px; height: 50px;" id="update-button" theme="success" label="Update"
            type="button" onclick="updateTicket()" />
    </div>
@endsection
@section('scripts')
    <script>
        const ticket = @json($ticket);
        const movie = @json($ticket->schedule->movie_id);
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
