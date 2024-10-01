@extends('layouts.admin')

@section('content')

<form method="post" action="/admin/permissions/create">
    @csrf
    <div class="mb-3">
      <label for="name" class="form-label">Permission name</label>
      <input type="text" class="form-control" name="name" >
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
  </form>

@endsection
