@extends('layouts.admin')
@section('styles')
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/schedules/schedules-edit.css') }}">
@endsection
@section('content')
    <form action="{{ route('schedules.update', $schedule->id) }}" method="post">
        @csrf
        @method('PUT')
        <div style="display: flex; flex-direction: column; align-items: start;margin-bottom: 20px;">
            <x-adminlte-input id="movie" name="movie" label="Movie" fgroup-class="w-100"
                data-duration="{{ $schedule->movie->duration }}" value="{{ $schedule->movie->name }}" disabled />
        </div>
        <div style="display: flex; flex-direction: row; align-items: start;margin-bottom: 20px; gap: 20px;">
            <div>
                <label for="datepicker">Select Date*</label>
                <div class="input-group date" id="datepicker" style="width: max-content !important;"
                    data-target-input="nearest">
                    @php
                        use Carbon\Carbon;
                        $formattedDate = $schedule->date ? Carbon::parse($schedule->date)->format('m/d/Y') : '';
                    @endphp
                    <x-adminlte-input id="date" name="date" type="text" class="form-control datetimepicker-input"
                        data-target="#datepicker" value="{{ $formattedDate }}" disabled />
                    <div style="height: 38px;" data-target="#datepicker" data-toggle="datetimepicker">
                        <div style="height: 38px;" class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            <x-adminlte-select id="auditorium" name="auditorium_id" label="Auditorium*" fgroup-class="w-100" disabled>
                @foreach ($auditoriums as $auditorium)
                    @if ($schedule->auditorium_id == $auditorium->id)
                        <option value="{{ $auditorium->id }}" selected>{{ $auditorium->name }}</option>
                    @else
                        <option value="{{ $auditorium->id }}">{{ $auditorium->name }}</option>
                    @endif
                @endforeach
            </x-adminlte-select>
        </div>
        <label for="timeline">Schedule</label>
        <div class="timeline"></div>
        </div>
        <x-adminlte-select id="showtime" class="select2" name="showtime_id[]" label="Showtime*" fgroup-class="w-100"
            multiple>
        </x-adminlte-select>
        <x-adminlte-button type="submit" label="Update" theme="primary" />
    </form>
@endsection
@section('scripts')
    <script>
        const showtimes = JSON.parse('{!! json_encode($schedule->showtimes) !!}');
        const scheduleId = String({{ $schedule->id }});
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js">
    </script>
    <script src="{{ asset('js/schedules/schedules-edit.js') }}"></script>
@endsection
