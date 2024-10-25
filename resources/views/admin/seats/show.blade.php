@extends('adminlte::page')
@section('content')
<x-adminlte-card title="Seat Details" theme="dark" icon="fas fa-lg fa-film">
    <p>Seat Number: <span style="font-weight: bold; color: rgb(56, 209, 69);">{{ $seat->seat_number }}</span></p>
    <p>Auditorium: <span style="font-weight: bold; color: rgb(73, 121, 211);">{{ $seat->auditorium->name }}</span></p>
    @php
        $heads = ['ID', 'Showtime', 'Movie', 'Date'];
    @endphp
    <label style="font-size: 1.2em; font-weight: bold; margin-bottom: 10px; margin-top: 20px;" for="table1">Information of the seat</label>
    <x-adminlte-datatable id="table1" :heads="$heads" head-theme="dark" striped hoverable bordered
        compressed>
        @foreach ($informations as $information)
            <tr>
                <td>{{ $information->showtime->id }}</td>
                <td>{{ $information->showtime->start_time }} - {{ $information->showtime->end_time }}</td>
                <td>{{ $information->movie }}</td>
                <td>{{ $information->date }}</td>
            </tr>
        @endforeach
    </x-adminlte-datatable>
    <x-adminlte-button label="Back" theme="dark" icon="fas fa-arrow-left" onclick="window.location.href='{{ route('seats.index') }}'" />
</x-adminlte-card>
@stop
