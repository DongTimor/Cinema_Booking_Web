@extends('layouts.admin')
@section('content')
    <div class="w-full flex justify-between mb-3 align-items-center">
        <h2>Ticket</h2>
        <x-adminlte-button style="width: 100px; height: 40px;" theme="success" label="Create"
            onclick="window.location.href = '/admin/tickets/create'" />
    </div>
    @php
        $heads = [
            ['label' => 'ID'],
            ['label' => 'Movie'],
            ['label' => 'Customer'],
            ['label' => 'Auditorium'],
            ['label' => 'Showtime', 'width' => 5],
            ['label' => 'Seat', 'width' => 5],
            ['label' => 'Status'],
            ['label' => 'Seller'],
            ['label' => 'Price'],
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];
        $config = [
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, null, null, null, null, null, null, ['orderable' => false]],
        ];
    @endphp
    <x-adminlte-datatable id="table1" :heads="$heads" head-theme="dark" :config="$config" striped hoverable bordered
        compressed>
        @foreach ($tickets as $ticket)
            <tr>
                <td>{!! $ticket->id !!}</td>
                <td><a
                        href="/admin/movies/features/show/{{ $ticket->schedule->movie->id }}">{{ $ticket->schedule->movie->name }}</a>
                </td>
                <td>
                    @if ($ticket->customer)
                        <a href="/admin/customers/{{ $ticket->customer->id }}">{{ $ticket->customer->name }}</a>
                    @else
                        Null
                    @endif
                </td>
                <td><a
                        href="/admin/auditoriums/show/{{ $ticket->schedule->auditorium->id }}">{{ $ticket->schedule->auditorium->name }}</a>
                </td>
                <td>{{ \Carbon\Carbon::parse($ticket->showtime->start_time)->format('H:i') }} -
                    {{ \Carbon\Carbon::parse($ticket->showtime->end_time)->format('H:i') }}</td>
                <td><a href="/admin/seats/{{ $ticket->seat_id }}">{{ $ticket->seat->seat_number }}</a></td>
                <td>{{ $ticket->status }}</td>
                @if ($ticket->user_id == auth()->user()->id)
                    <td><a href="/admin/profile">me</a></td>
                @else
                    <td>{{ $ticket->user->name }}</td>
                @endif
                <td>{{ $ticket->price }}</td>
                <td>
                    <nobr>
                        <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit"
                            onclick="editTicket({{ $ticket }})">
                            <i class="fa fa-lg fa-fw fa-pen"></i>
                        </button>
                        <button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete"
                            onclick="deleteTicket({{ $ticket }})">
                            <i class="fa fa-lg fa-fw fa-trash"></i>
                        </button>
                    </nobr>
                </td>
            </tr>
        @endforeach
    </x-adminlte-datatable>
@endsection
@section('scripts')
    <script src="{{ asset('js/tickets/index.js') }}"></script>
@endsection
