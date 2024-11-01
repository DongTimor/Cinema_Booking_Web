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
            <div class="modal-content modal-content-custom">
                <div class="modal-header">
                    <h5 class="modal-title" id="Event-ModalLabel">Event</h5>
                    <x-adminlte-button icon="fas fa-times" id="deleteButton" theme="danger" onclick="closeEventModal()" />
                </div>
                <div class="modal-body">
                    <x-adminlte-input id="title" name="title" label="Title" required />
                    <x-adminlte-textarea id="description" name="description" label="Description" required />
                    <input id="allday" type="checkbox" name="allday" checked />
                    <label for="allday">All Day</label>
                    <div class="form-group d-flex justify-content-between"
                        style="margin-top: 20px; width: max-content !important; gap: 50px">
                        <div class="form-group d-flex flex-column justify-content-between">
                            <label for="datetimepicker">Select Start Time</label>
                            <div class="input-group date" id="starttimepicker" style="width: max-content !important;"
                                data-target-input="nearest">
                                <input id="start_time" name="start_time" type="text"
                                    class="form-control datetimepicker-input" data-target="#starttimepicker" disabled />
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
                                <input id="end_time" name="end_time" type="text"
                                    class="form-control datetimepicker-input" data-target="#endtimepicker" disabled />
                                <div class="input-group-append" data-target="#endtimepicker" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                </div>
                                @error('end_time')
                                    <span>{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input id="all_movies" type="checkbox" name="all_movies" checked />
                        <label for="all_movies">All Movies</label>
                        <x-adminlte-select id="movies" class="select2" name="movies[]" label="Movies*"
                            style="width: 100%;" multiple disabled />
                    </div>
                    <x-adminlte-input id="discount-percentage" type="number" step="0.01" min="0" value="0"
                        name="discount-percentage" label="Discount Percentage" />
                    <div class="row">
                        <div class="col-md-6">
                            <x-adminlte-input id="number_of_tickets" type="number" min="0" step="1"
                                value="1" name="number_of_tickets" label="Number of Tickets" />
                        </div>
                        <div class="col-md-6">
                            <x-adminlte-input id="quantity" type="number" name="quantity" label="Quantity">
                                <x-slot name="bottomSlot">
                                    <span class="text-sm text-gray">
                                        If this field is empty, unlimited quantity!
                                    </span>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-adminlte-button id="apply-button" label="Apply" theme="success" onclick="applyEvent()" />
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Edit-Modal" tabindex="-1" role="dialog" aria-labelledby="Edit-ModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-custom">
                <div class="modal-header">
                    <h5 class="modal-title" id="Edit-ModalLabel">Event</h5>
                    <x-adminlte-button icon="fas fa-times" id="deleteButton" theme="danger"
                        onclick="closeEditModal()" />
                </div>
                <div class="modal-body">
                    <x-adminlte-input id="edit_title" name="title" label="Title" required />
                    <x-adminlte-textarea id="edit_description" name="description" label="Description" required />
                    <input id="edit_allday" type="checkbox" name="allday" />
                    <label for="edit_allday">All Day</label>
                    <div class="form-group d-flex justify-content-between"
                        style="margin-top: 20px; width: max-content !important; gap: 50px">
                        <div class="form-group d-flex flex-column justify-content-between">
                            <label for="datetimepicker">Select Start Time</label>
                            <div class="input-group date" id="edit_starttimepicker"
                                style="width: max-content !important;" data-target-input="nearest">
                                <input id="edit_start_time" name="start_time" type="text"
                                    class="form-control datetimepicker-input" data-target="#edit_starttimepicker" />
                                <div class="input-group-append" data-target="#edit_starttimepicker"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                </div>
                                @error('start_time')
                                    <span>{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group d-flex flex-column justify-content-between">

                            <label for="datetimepicker">Select End Time</label>
                            <div class="input-group date" id="edit_endtimepicker" style="width: max-content !important;"
                                data-target-input="nearest">
                                <input id="edit_end_time" name="end_time" type="text"
                                    class="form-control datetimepicker-input" data-target="#edit_endtimepicker" />
                                <div class="input-group-append" data-target="#edit_endtimepicker"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                </div>
                                @error('end_time')
                                    <span>{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input id="edit_all_movies" type="checkbox" name="all_movies" checked />
                        <label for="edit_all_movies">All Movies</label>
                        <x-adminlte-select id="edit_movies" class="select2" name="movies[]" label="Movies*"
                            style="width: 100%;" multiple disabled />
                    </div>
                    <x-adminlte-input id="edit_discount-percentage" type="number" step="0.01" min="0"
                        name="discount-percentage" label="Discount Percentage" />
                    <div class="row">
                        <div class="col-md-6">
                            <x-adminlte-input id="edit_number_of_tickets" type="number" min="0" step="1"
                                name="number_of_tickets" label="Number of Tickets" />
                        </div>
                        <div class="col-md-6">
                            <x-adminlte-input id="edit_quantity" type="number" name="quantity" label="Quantity">
                                <x-slot name="bottomSlot">
                                    <span class="text-sm text-gray">
                                        If this field is empty, unlimited quantity!
                                    </span>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-adminlte-button id="edit_apply-button" label="Apply" theme="success" onclick="applyEdit()" />
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="customEventModal" tabindex="-1" role="dialog" aria-labelledby="customEventModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-custom" style="min-width: 400px !important;">
                <div class="modal-header">
                    <h5 class="modal-title" id="customEventModalLabel" style="margin-left: -15px;">Custom Event</h5>
                    <x-adminlte-button icon="fas fa-times" id="closeCustomEventModal" style="margin-right: -25px;"
                        onclick="closeCustomEventModal()" />
                </div>
                <div class="modal-body" style="display: flex; justify-content: space-around;">
                    <x-adminlte-button style="width: 100px;" id="editButton" label="Show" theme="info"
                        onclick="editCustomEvent()" />
                    <x-adminlte-button style="width: 100px;" id="deleteButton" label="Delete" theme="danger"
                        onclick="deleteCustomEvent()" />
                </div>
            </div>
        </div>
    </div>
    <x-adminlte-modal id="modalCustom" title="Events Help" size="lg" theme="teal" icon="fas fa-bell" v-centered
        static-backdrop scrollable>
        <div style="font-size: 15px; font-weight: bold;">ADD NEW EVENT</div>
        <div>+ Click on the calendar to add a new event</div>
        <div style="font-size: 15px; font-weight: bold; margin-top: 10px;">EDIT EVENT</div>
        <div>+ Click on the event to edit or delete it</div>
        <div>+ Drag and drop the event to change its time</div>
        <div>+ Resize the event to change its duration</div>
        <x-slot name="footerSlot">
            <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal" />
        </x-slot>
    </x-adminlte-modal>
    <button id="addEventButton" class="bg-teal attention-button" data-toggle="modal"
        data-target="#modalCustom">Open helper</button>
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

            $('#edit_starttimepicker').datetimepicker({
                format: 'HH:mm'
            });
            $('#edit_endtimepicker').datetimepicker({
                format: 'HH:mm'
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/main.min.js"></script>
    <script src="{{ asset('js/events/index.js') }}"></script>
@endsection
