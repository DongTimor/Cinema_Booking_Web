@extends('layouts.admin')
@section('styles')
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css"
        rel="stylesheet">
    <style>
        .timeline {
            position: relative;
            width: 100%;
            height: 50px;
            background-color: #f4f4f9;
            border-top: 1px solid #ddd;
            border-right: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .timeline::before {
            content: none;
        }

        .showtime {
            position: absolute !important;
            top: 0;
            height: 100%;
            background-color: rgba(0, 123, 255, 0.5);
            border: 1px solid #007bff;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .ready {
            position: absolute !important;
            top: 0;
            height: 100%;
            background-color: rgba(236, 158, 55, 0.692);
            border: 1px solid #007bff;
            border-radius: 5px;
        }

        .start-time1 {
            position: absolute !important;
            left: -15px;
            bottom: -20px;
            font-size: 12px;
        }

        .end-time1 {
            position: absolute !important;
            right: -15px;
            bottom: -20px;
            font-size: 12px;
        }

        .start-time2 {
            position: absolute !important;
            left: -15px;
            top: -20px;
            font-size: 12px;
        }

        .end-time2 {
            position: absolute !important;
            right: -15px;
            top: -20px;
            font-size: 12px;
        }
    </style>
@endsection
@section('content')
    <form action="{{ route('schedules.store') }}" method="post">
        @csrf
        <div style="display: flex; flex-direction: column; align-items: start;margin-bottom: 20px;">
            <x-adminlte-select id="movie" name="movie_id" label="Movie*" fgroup-class="w-100">
                <option value="-1">--Select Movie First--</option>
                @foreach ($movies as $movie)
                    <option value="{{ $movie->id }}">{{ $movie->name }}</option>
                @endforeach
            </x-adminlte-select>
            <x-adminlte-button type="submit" label="Add new movie" theme="success" />
        </div>
        <div style="display: flex; flex-direction: row; align-items: start;margin-bottom: 20px; gap: 20px;">
            <div>
                <label for="datepicker">Select Date*</label>
                <div class="input-group date" id="datepicker" style="width: max-content !important;"
                    data-target-input="nearest">
                    <input id="date" placeholder="--Select Movie First--" disabled id="date" name="date"
                        type="text" class="form-control datetimepicker-input" data-target="#datepicker"
                        value="{{ old('date') }}" />
                    <div class="input-group-append" data-target="#datepicker" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                    @error('date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <x-adminlte-select id="auditorium" name="auditorium_id" label="Auditorium*" fgroup-class="w-100" disabled>
                <option value="-1">--Select Date First--</option>
                @foreach ($auditoriums as $auditorium)
                    <option value="{{ $auditorium->id }}">{{ $auditorium->name }}</option>
                @endforeach
            </x-adminlte-select>
        </div>
        <label for="timeline">Schedule</label>
        <div class="timeline">Schedule Empty</div>
        </div>
        <x-adminlte-select class="select2" name="showtime" label="Showtime*" fgroup-class="w-100" multiple>
            <option disabled value="-1">--Select Showtime First--</option>
        </x-adminlte-select>
        <x-adminlte-button type="submit" label="Create" theme="primary" />
    </form>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js">
    </script>
    <script type="text/javascript">
        $(function() {
            $('#datepicker').datetimepicker({
                format: 'MM/DD/YYYY'
            });
        });
    </script>
    <script src="{{ asset('js/schedules/schedules-create.js') }}"></script>
@endsection
