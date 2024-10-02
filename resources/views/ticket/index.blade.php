@extends('layouts.admin')

@section('content')
    <h2>Ticket</h2>
    @php
        $heads = [
            ['label' => 'ID'],
            ['label' => 'Movie'],
            ['label' => 'Customer'],
            ['label' => 'Auditorium'],
            ['label' => 'Showtime', 'width' => 5],
            ['label' => 'Seat', 'width' => 5],
            ['label' => 'Phone'],
            ['label' => 'Status'],
            ['label' => 'Seller'],
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        $config = [
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, null, null, null, null, null, null, ['orderable' => false]],
        ];
    @endphp

    <x-adminlte-datatable id="table1" :heads="$heads" head-theme="dark" :config="$config" striped hoverable bordered compressed>
        {{-- {{ dd($tickets->all()) }} --}}

        @foreach ($tickets as $ticket)


            <tr>
                {{-- {{ dd($ticket ) }} --}}
                <td>{!! $ticket->id !!}</td>
                <td><a href="">{{ $ticket->movie }}</a></td>
                <td><a href="">{{ $ticket->customer->name }}</a></td>
                <td><a href="">{{ $ticket->seat->auditorium->name }}</a></td>
                <td><a href="">{{ $ticket->showtime->id }}</a></td>
                <td><a href="">{{ $ticket->seat->seat_number }}</a></td>
                <td>{{ $ticket->customer->phone_number }}</td>
                <td>{{ $ticket->status }}</td>
                <td><a href="">{{ $ticket->user->name }}</a></td>
                <td>
                    <nobr>
                        <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="window.location.href='{{ route('tickets.edit', $ticket) }}'">
                            <i class="fa fa-lg fa-fw fa-pen"></i>
                        </button>
                        <button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="window.location.href='{{ url('/delete/' . $ticket) }}'">
                            <i class="fa fa-lg fa-fw fa-trash"></i>
                        </button>
                        <button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details" onclick="window.location.href='{{ url('/details/' . $ticket) }}'">
                            <i class="fa fa-lg fa-fw fa-eye"></i>
                        </button>
                    </nobr>
                </td>
            </tr>
        @endforeach
    </x-adminlte-datatable>
@endsection
