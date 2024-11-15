@extends('layouts.admin')

@section('content')
    <h3>Auditoriums</h3>

    <!-- Create Button -->
    <x-adminlte-button label="Create Auditorium" icon="fas fa-plus" theme="success" class="mb-4"
        onclick="window.location.href='{{ route('auditoriums.create') }}'" />

    <!-- Auditorium Table -->
    @php
        $heads = [
            'S.No',
            'Name',
            'Seats',
            'Status',
            ['label' => 'Actions', 'no-export' => true, 'width' => 10]
        ];
        
        $config = [
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null, ['orderable' => false]],
        ];
    @endphp

    <x-adminlte-datatable id="auditoriumTable" :heads="$heads" head-theme="dark" :config="$config" striped hoverable bordered compressed>
        @foreach ($auditoriums as $auditorium)
            <tr>
                <td>{{ $auditorium->id }}</td>
                <td>{{ $auditorium->name }}</td>
                <td>0/{{ $auditorium->total }}</td>
                <td>
                    <div class="d-flex align-items-center justify-content-center">
                        <span class="badge bg-warning">Maintenance</span>
                    </div>
                </td>
                <td class="flex items-center justify-center">
                    <nobr>
                        <x-adminlte-button theme="primary" icon="fas fa-edit" 
                            onclick="window.location.href='{{ route('auditoriums.edit', $auditorium->id) }}'" class="btn-xs mx-1" />
                        
                        <form action="{{ route('auditoriums.destroy', $auditorium->id) }}" method="POST" style="display:inline;">
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
