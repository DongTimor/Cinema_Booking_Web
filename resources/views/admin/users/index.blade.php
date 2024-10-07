@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-end p-3">

    <button type="button" class="btn btn-outline-primary">
        <a href="{{route('users.create')}}">
            Create
        </a>
    </button>
</div>

    <table class="table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
        </tr>
         @foreach ($users as $item)
            <tr>
              <td>
                {{ $item->id }}
              </td>
              <td>
                {{ $item->name }}
              </td>
              <td>
                {{ $item->email }}
              </td>
              <td>
                {{ $item->flag_deleted }}
              </td>
            </tr>
        @endforeach
    </table>
@endsection
