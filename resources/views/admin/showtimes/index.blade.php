@extends('layouts.admin')

@section('content')
    <h3>Showtime</h3>
    @php
        $heads = [['label' => 'ID'], ['label' => 'Time'], ['label' => 'Actions', 'no-export' => true, 'width' => 5]];

        $config = [
            'order' => [[1, 'asc']],
            'columns' => [null, null, ['orderable' => false]],
        ];
    @endphp
    <x-adminlte-button style="margin-bottom: 10px" label="Create" icon="fas fa-plus" theme="success"
        onclick="window.location.href='{{ route('showtimes.create') }}'" />
    <x-adminlte-datatable id="table1" :heads="$heads" head-theme="dark" :config="$config" striped hoverable bordered
        compressed>

        @foreach ($showtimes as $showtime)
            <tr>
                <td>{!! $showtime->id !!}</td>
                <td>{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }} -
                    {{ \Carbon\Carbon::parse($showtime->end_time)->format('H:i') }}</td>
                <td>
                    <nobr style="display: flex; flex-direction: row">
                        <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit"
                            onclick="window.location.href='{{ route('showtimes.edit', $showtime) }}'">
                            <i class="fa fa-lg fa-fw fa-pen"></i>
                        </button>
                        <form action="{{ route('showtimes.destroy', $showtime) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-xs btn-default text-danger mx-1 shadow" type="submit" title="Delete">
                                <i class="fa fa-lg fa-fw fa-trash"></i>
                            </button>
                        </form>
                    </nobr>
                </td>
            </tr>
        @endforeach
    </x-adminlte-datatable>
@endsection
