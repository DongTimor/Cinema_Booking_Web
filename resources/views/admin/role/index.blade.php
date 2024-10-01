@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-end p-3">

    <button type="button" class="btn btn-outline-primary"><a href="{{route('roles.create')}}">Create</a></button>
</div>

    <table class="table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
         @foreach ($roles as $item)
            <tr>
              <td>
                {{ $item->id }}
              </td>
              <td>
                <a href="{{ route('roles.show', $item->id) }}">{{ $item->name }}</a>
              </td>
              <td>
                {{ $item->flag_deleted }}
              </td>
              <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                      ...
                    </button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="{{ route('roles.show', $item->id) }}">Edit</a></li>
                      <li>
                        <a class="dropdown-item delete-btn" data-id={{$item->id}} href="javascript:void(0);">Delete</a>
                      </li>
                    </ul>
                  </div>
              </td>
            </tr>
        @endforeach
    </table>
@endsection

@section('script')
    <script src="{{asset("/js/deleteajax.js")}}" ></script>
@endsection
