@extends('layouts.admin')
@section('styles')
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/schedules/create.css') }}">
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
            <x-adminlte-button type="button" label="Add new movie" theme="success"
                onclick="window.location.href='/admin/movies/features/create'" />
        </div>
        <div style="display: flex; flex-direction: row; align-items: start;margin-bottom: 20px; gap: 20px;">
            <div>
                <label for="datepicker">Select Date*</label>
                <div class="input-group date" id="datepicker" style="width: max-content !important;"
                    data-target-input="nearest">
                    <x-adminlte-input id="date" placeholder="--Select Movie First--" disabled id="date"
                        name="date" typFe="text" class="form-control datetimepicker-input" data-target="#datepicker"
                        value="{{ old('date') }}" />
                    <div style="height: 38px;" data-target="#datepicker" data-toggle="datetimepicker">
                        <div style="height: 38px;" class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
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
        <x-adminlte-select id="showtime" class="select2" name="showtime_id[]" label="Showtime*" fgroup-class="w-100"
            multiple>
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
    <script src="{{ asset('js/schedules/create.js') }}"></script>
@endsection
