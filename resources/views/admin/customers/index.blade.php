@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-end p-3">
    <button type="button" class="btn btn-outline-primary">
        <a href="{{ route('customers.create') }}">Create</a>
    </button>
</div>

<table class="table">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Phone Number</th>
        <th>Address</th>
        <th>Gender</th>
        <th>Day Of Birth</th>
        <th>Email</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    @foreach ($customers as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>
                <a href="{{ route('customers.edit', $item->id) }}">{{ $item->name }}</a>
            </td>
            <td>
                {{ $item->phone_number }}
            </td>
            <td>
                {{ $item->address }}
            </td>
            <td>
                {{ $item->gender }}
            </td>
            <td>
                {{ $item->date_of_birth }}
            </td>
            <td>
                {{ $item->email }}
            </td>
            <td>
                {{ $item->status }}
            </td>
            <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        ...
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('customers.edit', $item->id) }}">Edit</a>
                        </li>
                        <li>
                            <form action="{{ route('customers.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role: {{ $item->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item">Delete</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
    @endforeach
</table>
@endsection
