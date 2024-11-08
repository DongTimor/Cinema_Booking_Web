@extends('layouts.admin')

@section('content')
    <h3>Movie Categories</h3>

    <!-- Create Button -->
    <x-adminlte-button label="Add Category" icon="fas fa-plus" theme="success" class="mb-4"
        onclick="window.location.href='{{ route('movies.categories.create') }}'" />

    <!-- Categories Table -->
    @php
        $heads = [
            'S.No',
            'Name',
            ['label' => 'Actions', 'no-export' => true, 'width' => 10]
        ];
        
        $config = [
            'order' => [[0, 'asc']],
            'columns' => [null, null, ['orderable' => false]],
        ];
    @endphp

    <x-adminlte-datatable id="categoryTable" :heads="$heads" head-theme="dark" :config="$config" striped hoverable bordered compressed>
        @foreach ($categories as $category)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $category->name }}</td>
                <td class="flex items-center justify-center">
                    <nobr>
                        <x-adminlte-button theme="primary" icon="fas fa-edit"
                            onclick="window.location.href='{{ route('movies.categories.edit', $category->id) }}'" class="btn-xs mx-1" />
                        
                        <form action="{{ route('movies.categories.destroy', $category->id) }}" method="POST" style="display:inline;">
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
