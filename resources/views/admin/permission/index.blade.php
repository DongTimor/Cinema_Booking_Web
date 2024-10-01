@extends('layouts.admin')

@section('content')

    <div class="d-flex justify-content-end p-3">

        <button type="button" class="btn btn-outline-primary"><a href="{{route('permissions.create')}}">Create</a></button>
    </div>

    <table class="table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Action</th>
        </tr>
         @foreach ($permissions as $item)
            <tr>
              <td>
                {{ $item->id }}
              </td>
              <td>
                {{ $item->name }}
              </td>
              <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                      ...
                    </button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="{{ route('permissions.show', $item->id) }}">Edit</a></li>
                      <li><a class="dropdown-item" href="#">Delete</a></li>
                    </ul>
                  </div>
              </td>
            </tr>
        @endforeach
    </table>
@endsection
