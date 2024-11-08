@extends('layouts.admin')

@section('content')
    <h3>Movie Features</h3>
    <x-adminlte-button label="Add Movie" icon="fas fa-plus" theme="success" class="mb-4"
        onclick="window.location.href='{{ route('movies.features.create') }}'" />
    @php
        $heads = [
            'S.No',
            'Name',
            'Duration',
            'Start Date',
            'End Date',
            'Status',
            ['label' => 'Actions', 'no-export' => true, 'width' => 10]
        ];
        
        $config = [
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null, null, null, ['orderable' => false]],
        ];
    @endphp

    <x-adminlte-datatable id="movieFeatureTable" :heads="$heads" head-theme="dark" :config="$config" striped hoverable bordered compressed>
        @foreach ($movies as $movie)
            <tr>
                <td>{{ $movie->id }}</td>
                <td>{{ $movie->name }}</td>
                <td>{{ $movie->duration }}</td>
                <td>{{ $movie->start_date }}</td>
                <td>{{ $movie->end_date }}</td>
                <td class="text-center">
                    @if ($movie->status == 'impending')
                        <span class="badge bg-warning">{{ $movie->status }}</span>
                    @elseif ($movie->status == 'active')
                        <span class="badge bg-success">{{ $movie->status }}</span>
                    @elseif ($movie->status == 'inactive')
                        <span class="badge bg-danger">{{ $movie->status }}</span>
                    @else
                        <span class="badge bg-secondary">{{ $movie->status }}</span>
                    @endif
                </td>
                <td class="flex items-center justify-center">
                    <nobr>
                        <x-adminlte-button theme="primary" icon="fas fa-edit"
                            onclick="window.location.href='{{ route('movies.features.edit', $movie->id) }}'" class="btn-xs mx-1" />
                        
                        <form action="{{ route('movies.features.destroy', $movie->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <x-adminlte-button type="submit" theme="danger" icon="fas fa-trash"
                                class="btn-xs mx-1" />
                        </form>
                    </nobr>
                </td>
            </tr>
        @endforeach
    </x-adminlte-datatable>
@endsection
