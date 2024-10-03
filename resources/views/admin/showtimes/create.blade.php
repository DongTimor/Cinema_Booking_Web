@extends('layouts.admin')
@section('styles')
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css"
        rel="stylesheet">
@endsection
@section('content')
    <form action="{{ route('showtimes.store') }}" method="post">
        @csrf
        <div class="form-group d-flex justify-content-between" style="width: max-content !important; gap: 50px">
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
                    <input name="end_time" type="text" class="form-control datetimepicker-input" data-target="#endtimepicker" />
                    <div class="input-group-append" data-target="#endtimepicker" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-clock"></i></div>
                    </div>
                    @error('end_time')
                        <span>{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <x-adminlte-button type='submit' label='Create' theme="success" icon="fas fa-plus"></x-adminlte-button>
    </form>
@endsection


@section('scripts')
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
@endsection
