@extends('layouts.admin')

@section('content')
    <h2 style="margin-bottom: 10px;">Seat</h2>
    @if (session('success'))
        <x-adminlte-alert theme="success" title="Success" dismissable>
            {{ session('success') }}
        </x-adminlte-alert>
    @endif
    @if (session('error'))
        <x-adminlte-alert theme="danger" title="Error" dismissable>
            {{ session('error') }}
        </x-adminlte-alert>
    @endif
    @php
        $heads = [
            ['label' => 'ID'],
            ['label' => 'Auditorium'],
            ['label' => 'Seat Number'],
            ['label' => 'Status'],
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        $config = [
            'seats' => [$seats],
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, null, ['orderable' => false]],
        ];
    @endphp
    <div style="margin-bottom: 10px;">
        <x-adminlte-button label="Create" theme="success" icon="fas fa-plus" onclick="window.location.href='{{ route('seats.singleCreate') }}'" />
        <x-adminlte-button label="Auto Create" theme="info" icon="fas fa-plus" onclick="window.location.href='{{ route('seats.create') }}'" />
    </div>
    <x-adminlte-datatable id="table1" :heads="$heads" head-theme="dark" :config="$config" striped hoverable bordered
        compressed>
        @foreach ($config['seats'] as $seat)
            @foreach ($seat as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td><a href="{{ route('auditoriums.show', $row->auditorium->id) }}">{{ $row->auditorium->name }}</a></td>

                    <td>{{ $row->seat_number }}</td>

                    <td>
                        @isset($row->ticket)
                            {{ $row->ticket->status }}
                        @else
                            unplaced
                        @endisset
                    </td>
                    <td>
                        <nobr>
                            <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit"
                                onclick="window.location.href='{{ route('seats.edit', $row) }}'">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </button>
                            <button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details"
                                onclick="window.location.href='{{ route('seats.show', $row) }}'">
                                <i class="fa fa-lg fa-fw fa-eye"></i>
                            </button>
                            <form action="{{ route('seats.destroy', $row) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow"
                                    title="Delete">
                                    <i class="fa fa-lg fa-fw fa-trash"></i>
                                </button>
                            </form>
                        </nobr>
                    </td>
                </tr>
            @endforeach
        @endforeach
    </x-adminlte-datatable>
@endsection
