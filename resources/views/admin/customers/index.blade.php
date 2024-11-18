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
        <th class="text-center">Action</th>
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
                {{ $item->birth_date }}
            </td>
            <td>
                <ul class="d-flex justify-content-center mb-0">
                    <li>
                        <a class="btn btn-outline-primary mr-2" href="{{ route('customers.edit', $item->id) }}"
                            role="button"><i class="fas fa-tools"></i> Edit</a>
                    </li>
                    <li>
                        <form action="{{ route('customers.destroy', $item->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this voucher: {{ $item->name }}?');">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn btn-outline-danger"><i class="far fa-trash-alt"></i>
                                Delete</button>
                        </form>
                    </li>
                </ul>
            </td>
        </tr>
    @endforeach
</table>
@endsection
