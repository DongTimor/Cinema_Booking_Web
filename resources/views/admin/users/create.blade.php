@extends('layouts.admin')

@section('content')

<form method="post" action="/admin/users/create">
    @csrf
    <div class="mb-3">
      <label for="name" class="form-label">User Name</label>
      <input type="text" class="form-control" name="name" >
    @if ($errors->has('name'))
        <span class="text-danger">{{ $errors->first('name') }}</span>
    @endif

    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" name="email" >
    @if ($errors->has('email'))
        <span class="text-danger">{{ $errors->first('email') }}</span>
    @endif
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" >
    @if ($errors->has('password'))
        <span class="text-danger">{{ $errors->first('password') }}</span>
    @endif
    </div>

    <label for="roles" class="form-label">Roles</label>
    <x-adminlte-select name="roles" class='select2'>
        @foreach ($roles as $item)
        <option value="{{$item}}">{{$item}}</option>
        @endforeach
    </x-adminlte-select>
    <button type="submit" class="btn btn-primary">Create</button>
  </form>

@endsection
