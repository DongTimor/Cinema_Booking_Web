@extends('layouts.admin')
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/main.min.css">
    <link rel="stylesheet" href="{{ asset('css/schedules/index.css') }}">
@endsection
@section('content')
    <div class="d-flex justify-content-start align-items-center gap-3">
        <x-adminlte-select id="movieFilter" label="Movie filter" name="movie_id">
            <option value="">All</option>
            @foreach ($movies as $movie)
                <option value="{{ $movie->id }}">{{ $movie->name }}</option>
            @endforeach
        </x-adminlte-select>
        <x-adminlte-select id="auditoriumFilter" label="Auditorium filter" name="auditorium_id">
            <option value="">All</option>
            @foreach ($auditoriums as $auditorium)
                <option value="{{ $auditorium->id }}">{{ $auditorium->name }}</option>
            @endforeach
        </x-adminlte-select>
        <x-adminlte-button class="ml-auto h-100 mr-3" id="createButton" label="Create" theme="success"
            onclick="window.location.href='/admin/schedules/create'" />
    </div>
    <div class="container">
        <div id="calendar"></div>
    </div>
    <div class="modal fade" id="customWeekModal" tabindex="-1" role="dialog" aria-labelledby="customWeekModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customWeekModalLabel">Custom Schedule</h5>
                </div>
                <div class="modal-body">
                    <x-adminlte-button id="editButton" label="Edit" theme="info" onclick="editSchedule()" />
                    <x-adminlte-button id="showButton" label="Show" theme="success" onclick="showSchedule()" />
                    <x-adminlte-button id="deleteButton" label="Delete" theme="danger" onclick="deleteSchedule()" />
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/main.min.js"></script>
    <script>
        const schedules = JSON.parse('{!! json_encode($schedules) !!}');
    </script>
    <script src="{{ asset('js/schedules/index.js') }}"></script>
@endsection
