@extends('layouts.admin')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/events/index.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/main.min.css">
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css"
        rel="stylesheet">
@endsection
@section('content')
    <div class="container">
        <div id="calendar"></div>
    </div>
    <div class="modal fade" id="Event-Modal" tabindex="-1" role="dialog" aria-labelledby="Event-ModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="Event-ModalLabel">Event</h5>
                    <x-adminlte-button icon="fas fa-times" id="deleteButton" theme="danger" onclick="closeEventModal()" />
                </div>
                <div class="modal-body">
                    <x-adminlte-input id="title" name="title" label="Title" required />
                    <x-adminlte-textarea id="description" name="description" label="Description" required />
                    <x-adminlte-input type="number" id="total-days" name="total-days" label="Total Days" />
                    <input type="checkbox" id="allday" name="allday" />
                    <label for="allday">All Day</label>
                    <div class="form-group d-flex justify-content-between"
                        style="margin-top: 20px; width: max-content !important; gap: 50px">
                        <div class="form-group d-flex flex-column justify-content-between">
                            <label for="datetimepicker">Select Start Time</label>
                            <div class="input-group date" id="starttimepicker" style="width: max-content !important;"
                                data-target-input="nearest">
                                <input name="start_time" type="text" class="form-control datetimepicker-input"
                                    data-target="#starttimepicker" />
                                <div class="input-group-append" data-target="#starttimepicker" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                </div>
                                @error('start_time')
                                    <span>{{ $message }}</span>
                                @enderror
                            </div>


                        </div>
                        <div class="form-group d-flex flex-column justify-content-between">

                            <label for="datetimepicker">Select End Time</label>
                            <div class="input-group date" id="endtimepicker" style="width: max-content !important;"
                                data-target-input="nearest">
                                <input name="end_time" type="text" class="form-control datetimepicker-input"
                                    data-target="#endtimepicker" />
                                <div class="input-group-append" data-target="#endtimepicker" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                </div>
                                @error('end_time')
                                    <span>{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <x-adminlte-input type="color" value="#3cec48" id="background-color" name="background-color"
                                label="Background Color" />
                        </div>
                        <div class="col-md-6">
                            <x-adminlte-input type="color" value="#ffffff" id="text-color" name="text-color"
                                label="Text Color" />
                        </div>
                    </div>
                    <x-adminlte-input type="number" id="discount-percentage" step="0.01" min="0"
                        name="discount-percentage" label="Discount Percentage" />
                    <div class="row">
                        <div class="col-md-6">
                            <x-adminlte-input type="number" id="number-of-tickets" min="0" step="1"
                                name="number-of-tickets" label="Number of Tickets" />
                        </div>
                        <div class="col-md-6">
                            <x-adminlte-input type="number" id="quantity" name="quantity" label="Quantity" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-adminlte-button id="apply-button" label="Apply" theme="success" onclick="applyEvent()" />
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        const events = @json($events);
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js">
    </script>
    <script type="text/javascript">
        $(function() {
            $('#starttimepicker').datetimepicker({
                format: 'HH:mm'
            });
            $('#endtimepicker').datetimepicker({
                format: 'HH:mm'
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/main.min.js"></script>
    <script src="{{ asset('js/events/index.js') }}"></script>
@endsection
