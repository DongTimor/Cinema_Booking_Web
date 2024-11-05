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
                        <div>
                            <label style="margin-right: 10px;" for="customer_id">Customer</label>
                            <x-adminlte-button type="button" icon="fas fa-plus" theme="success"
                                onclick="window.location.href='/admin/customers/create'" />
                        </div>
                        <div class="area">
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
                            <button id="show-voucher-button" type="button" class="btn btn-primary"
                                onclick="openShowVoucherModal()" style="display: none;">Show
                                Voucher</button>
                            <div id="voucher-body" class="voucher-body" style="display: none !important;">
                                <div class="voucher-background"
                                    style="background-image: url('{{ asset('images/voucher_background.jpg') }}');">
                                    <button class="close-button" type="button" class="close" onclick="deleteVoucher()"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h1 class="label">Voucher Giảm Giá</h1>
                                    <p id="voucher-description" class="description">Nhận ngay cho đơn hàng tiếp theo!
                                    </p>
                                    <div id="voucher-code" class="code text-uppercase">ABCDF</div>
                                    <div id="voucher-expiry" class="expiry">Hết hạn: 2024-10-22</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-7">
                                    <x-adminlte-input type="text" name="customer-name" id="customer-name" label="Name"
                                        disabled />
                                </div>
                                <div class="col-md-3">
                                    <x-adminlte-input id="customer-date-of-birth" type="date"
                                        name="customer-date-of-birth" label="Date of Birth" disabled />
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
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-10">
                                        <x-adminlte-select id="movie_name" name="movie_name" label="Movie" required>
                                            <option value="">-Select Movie-</option>
                                            @foreach ($movies as $movie)
                                                <option value="{{ $movie->id }}">{{ $movie->name }}</option>
                                            @endforeach
                                        </x-adminlte-select>
                                    </div>
                                    <div style="width: min-content;"
                                        class="d-flex justify-content-start align-items-center pt-3">
                                        <x-adminlte-button type="button" icon="fas fa-plus" theme="success"
                                            onclick="window.location.href='/admin/movies/features/create'" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <x-adminlte-select id="movie_id" name="movie_id" label="Movie ID" required>
                                    <option value="">-Select Movie-</option>
                                    @foreach ($movies as $movie)
                                        <option value="{{ $movie->id }}">{{ $movie->id }}</option>
                                    @endforeach
                                </x-adminlte-select>
                            </div>
                            <div class="col-md-1">
                                <x-adminlte-input id="price" type="number" step="0.01" name="price"
                                    label="Price" disabled />
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
                                    <x-adminlte-select id="auditorium_id" name="auditorium_id" label="Auditorium"
                                        required disabled>
                                        <option value="">-Select Auditorium-</option>
                                    </x-adminlte-select>
                                </div>
                            </div>
                        </div>
                        <div class="seats_container_lable">
                            ----------Seats's Booking----------
                        </div>
                        <div class="guide-description">
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

                        <div id="seats_container" class="seats_container">
                            <p>Available Seats</p>
                        </div>
                        <div id="another_seats_container_lable" class="seats_container_lable"
                            style="display: none !important;">
                            ----------Another Seats's Booking----------
                        </div>
                        <div id="another_seats_container" class="anotherseats_container">
                        </div>
                        <div class="create-button-container">
                            <x-adminlte-button type="button" theme="success" label="Create" onclick="createTicket()" />
                        </div>
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
                    <x-adminlte-button id="back-to-index" label="All Tickets" theme="success" onclick="backToIndex()" />
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Show-Voucher-Modal" tabindex="-1" role="dialog"
        aria-labelledby="Show-Voucher-ModalLabel" aria-hidden="true">
        <div class="modal-dialog main-dialog" role="document">
            <div class="modal-content main-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="Show-Voucher-ModalLabel">Voucher List</h5>
                    <button type="button" class="close" onclick="closeShowVoucherModal()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body main-body">
                    <ul id="voucher-list" class="list-group">
                    </ul>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <x-adminlte-modal id="eventModal" title="Available Events" size="lg" theme="teal" icon="fas fa-bell" v-centered
        static-backdrop scrollable>
        @php
            $heads = ['ID', 'Name', 'Start Time', 'End Time', 'Number of Tickets', 'Quantity', 'Discount'];
            $config = [
                'order' => [[0, 'desc']],
                'columns' => [null, null, null, null, null, null, null],
            ];

        @endphp
        <x-adminlte-datatable id="datatable" :heads="$heads" head-theme="dark" :config="$config" striped hoverable
            bordered compressed />
        <x-slot name="footerSlot">
            <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal" />
        </x-slot>
    </x-adminlte-modal>
    <button style="display: none" id="availableEventsButton" class="bg-teal attention-button"
        data-toggle="modal" data-target="#eventModal">Available Events</button>
@stop
@section('scripts')
    <script>
        $(document).ready(function() {
            function formatOption(option) {
                if (!option.id) {
                    return option.text;
                }
                var imageUrl = $(option.element).data('image');
                var $option = $(
                    '<span><img src="' + imageUrl +
                    '" class="img-flag" style="width: 20px; height: 20px; margin-right: 10px;" /> ' + option
                    .text + '</span>'
                );
                return $option;
            }
            $('#voucher-select').select2({
                templateResult: formatOption,
                templateSelection: formatOption
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js">
    </script>
    <script src="{{ asset('js/tickets/create.js') }}"></script>
@endsection
