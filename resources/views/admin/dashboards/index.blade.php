@extends('layouts.admin')

@section('content')
    <h3>User Activity Logs</h3>

    <!-- Activity Logs Table -->
    @php
        $heads = ['S.No', 'User ID', 'Activity', 'URL', 'Created At'];

        $config = [
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, ['orderable' => false], null],
        ];
    @endphp

    <x-adminlte-datatable id="activityLogTable" :heads="$heads" head-theme="dark" :config="$config" striped hoverable
        bordered compressed>
        @foreach ($dashboards as $dashboard)
            <tr>
                <td>{{ $dashboard->id }}</td>
                <td>{{ $dashboard->user_id }}</td>
                <td>{{ $dashboard->activity }}</td>
                <td class="flex items-center justify-center">
                    @if ($dashboard->url)
                        <a href="{{ $dashboard->url }}" target="_blank" class="text-green-500 hover:underline"> <i
                                class="fa fa-lg fa-fw fa-eye"></i></a>
                    @else
                        <span>-</span>
                    @endif
                </td>
                <td>{{ $dashboard->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
        @endforeach
    </x-adminlte-datatable>
@endsection
