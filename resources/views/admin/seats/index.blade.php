@extends('layouts.admin')

@section('content')
    <h2>Seat</h2>
    @php
        $heads = [
            ['label' => 'ID'],
            ['label' => 'Seat Number'],
            ['label' => 'Auditorium'],
            ['label' => 'Status'],
            ['label' => 'Customer'],
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        $btnEdit = '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="window.location.href=\'/\'">
                <i class="fa fa-lg fa-fw fa-pen"></i>
            </button>';
        $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="window.location.href=\'/\'">
                  <i class="fa fa-lg fa-fw fa-trash"></i>
              </button>';
        $btnDetails = '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details" onclick="window.location.href=\'/\'">
                   <i class="fa fa-lg fa-fw fa-eye"></i>
               </button>';

        $config = [
            'seats' => [$seats],
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, null, null, null, ['orderable' => false]],
        ];
    @endphp

    <x-adminlte-datatable id="table1" :heads="$heads" head-theme="dark" :config="$config" striped hoverable bordered
        compressed>
        @foreach ($config['seats'] as $seat)
            @foreach ($seat as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->seat_number }}</td>

                    <td><a href="">{{ $row->auditorium->name }}</a></td>
                    <td>
                        @isset($row->ticket)
                            {{ $row->ticket->status }}
                        @else
                            unplaced
                        @endisset
                    </td>
                    <td>
                        @isset($row->ticket)
                            <a href="">{{ $row->ticket->customer->name }}</a>
                        @else
                        @endisset
                    </td>
                    <td>
                        <nobr>
                            {!! $btnEdit !!}
                            {!! $btnDelete !!}
                            {!! $btnDetails !!}
                        </nobr>
                    </td>
                </tr>
            @endforeach
        @endforeach
    </x-adminlte-datatable>
@endsection
