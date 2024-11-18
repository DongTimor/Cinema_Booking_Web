@extends('layouts.admin')

@section('content')

    <div class="d-flex justify-content-end p-3">

        <button type="button" class="btn btn-outline-primary"><a href="{{route('permissions.create')}}">Create</a></button>
    </div>

    <table class="table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th class="text-center">Action</th>
        </tr>
         @foreach ($permissions as $item)
            <tr>
              <td>
                {{ $item->id }}
              </td>
              <td>
                <a class="dropdown-item" href="{{ route('permissions.show', $item->id) }}">{{ $item->name }}</a>
              </td>
              <td>
                <ul class="d-flex justify-content-center mb-0">
                    <li>
                        <a class="btn btn-outline-primary mr-2" href="{{ route('permissions.show', $item->id) }}"
                            role="button"><i class="fas fa-tools"></i> Edit</a>
                    </li>
                    <li>
                        <form action="{{ route('permissions.destroy', $item->id) }}" method="POST"
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
