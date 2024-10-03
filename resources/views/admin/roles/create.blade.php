@extends('layouts.admin')

@section('content')

<form method="post" action="/admin/roles/create">
    @csrf
    <div class="mb-3">
      <label for="name" class="form-label">Role name</label>
      <input type="text" class="form-control" name="name" >
    </div>

    <x-adminlte-select name="permissions[]" class='select2' multiple>
        @foreach ($permissions as $item)
        <option value="{{$item->id}}">{{$item->name}}</option>
        @endforeach
    </x-adminlte-select>
    <button type="submit" class="btn btn-primary">Create</button>
  </form>

@endsection
