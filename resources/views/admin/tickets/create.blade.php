@extends('layouts.admin')
@section('styles')
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/tickets/create.css') }}">
@endsection
@section('content')
    <div style="height: 100%">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Create Ticket</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <x-adminlte-input name="user_name" label="Sealer" value="{{ Auth::user()->name }}"
                                    disabled />
                            </div>
                            <div class="col-md-2">
                                <x-adminlte-input style="text-align: center;" name="user_id" label="Sealer ID"
                                    value="{{ Auth::user()->id }}" disabled />
                            </div>
                        </div>
                        <label for="customer_id">Customer</label>
                        <div
                            style="margin-bottom: 20px; background-color:rgba(170, 160, 160, 0.062); padding: 10px; border-radius: 10px;">
                            <div class="row">
                                <div class="col-md-10">
                                    <x-adminlte-select name="customer_id" required>
                                        <option value="">-Select Customer-</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </x-adminlte-select>
                                </div>
                                <span style="width: max-content; align-items: center;">Or</span>
                                <div style="width: max-content; justify-content: flex-end;">
                                    <x-adminlte-button id="switch-customer-input" label="No Account" theme="success"
                                        onclick="switchCustomerInput()" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-7">
                                    <x-adminlte-input type="text" name="customer-name" id="customer-name" label="Name"
                                        disabled />
                                </div>
                                <div class="col-md-3">
                                    <x-adminlte-input type="date" name="customer-date-of-birth"
                                        id="customer-date-of-birth" label="Date of Birth" disabled />
                                </div>
                                <div class="col-md-2">
                                    <x-adminlte-input type="text" name="customer-gender" id="customer-gender"
                                        label="Gender" disabled />
                                </div>
                            </div>
                            <x-adminlte-input type="email" name="customer-email" id="customer-email" label="Email"
                                disabled />
                            <div class="row">
                                <div class="col-md-4">
                                    <x-adminlte-input type="text" name="customer-phone" id="customer-phone"
                                        label="Phone" disabled />
                                </div>
                                <div class="col-md-8">
                                    <x-adminlte-input type="text" name="customer-address" id="customer-address"
                                        label="Address" disabled />
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <x-adminlte-select id="movie_name" name="movie_name" label="Movie" required>
                                    <option value="">-Select Movie-</option>
                                    @foreach ($movies as $movie)
                                        <option value="{{ $movie->id }}">{{ $movie->name }}</option>
                                    @endforeach
                                </x-adminlte-select>
                            </div>
                            <div class="col-md-4">
                                <x-adminlte-select id="movie_id" name="movie_id" label="Movie ID" required>
                                    <option value="">-Select Movie-</option>
                                    @foreach ($movies as $movie)
                                        <option value="{{ $movie->id }}">{{ $movie->id }}</option>
                                    @endforeach
                                </x-adminlte-select>
                            </div>
                            <div class="flex gap-1">
                                <div class="col-md-2">
                                    <x-adminlte-select id="date" name="date" label="Date" required disabled>
                                        <option value="">-Select Date-</option>
                                    </x-adminlte-select>
                                </div>
                                <div class="col-md-5">
                                    <x-adminlte-select id="showtime_id" name="showtime_id" label="Showtime" required
                                        disabled>
                                        <option value="">-Select Showtime-</option>
                                    </x-adminlte-select>
                                </div>
                                <div class="w-full">
                                    <x-adminlte-select id="auditorium_id" name="auditorium_id" label="Auditorium" required
                                        disabled>
                                        <option value="">-Select Auditorium-</option>
                                    </x-adminlte-select>
                                </div>
                                <div class="col-md-1">
                                    <x-adminlte-input type="number" step="0.01" min="0" name="price"
                                        id="price" label="Price" />
                                </div>
                            </div>
                        </div>
                        <div class="seats_container_lable">
                            ----------Seats's Booking----------
                        </div>
                        <div id="seats_container" class="seats_container">
                            <p>Available Seats</p>
                        </div>
                        <div id="another_seats_container_lable" class="seats_container_lable"
                            style="display: none !important;">
                            ----------Another Seats's Booking----------
                        </div>
                        <div id="another_seats_container" class="anotherseats_container">
                        </div>
                        <button type="button" class="btn btn-primary" onclick="createTicket()">Create</button>
                    </div>
                </div>
            </div>
        </div>
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
                        <option value="ordered">Ordered</option>
                        <option value="settled">Settled</option>
                    </x-adminlte-select>
                </div>
                <div class="modal-footer">
                    <x-adminlte-button id="apply-status-button" label="Apply" theme="success"
                        onclick="applyStatus()" />
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Fetch-Seats-Modal" tabindex="-1" role="dialog"
        aria-labelledby="Fetch-Seats-ModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="Fetch-Seats-ModalLabel">Fetch Seats</h5>
                    <x-adminlte-button icon="fas fa-times" id="deleteButton" theme="danger"
                        onclick="closeFetchSeatsModal()" />
                </div>
                <div class="modal-body">
                    <p id="successCount">Success: 0 seats</p>
                    <p id="errorCount">Error: 0 seats</p>
                    <ul id="successList"></ul>
                    <ul id="errorList"></ul>
                </div>
                <div class="modal-footer">
                    <x-adminlte-button id="back-to-index" label="All Tickets" theme="success"
                        onclick="backToIndex()" />
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js">
    </script>
    <script src="{{ asset('js/tickets/create.js') }}"></script>
@endsection
