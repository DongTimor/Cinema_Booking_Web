@extends('layouts.admin')

@section('content')

<form method="post" action="/admin/permissions/{{ $permission->id }}">
    @csrf
    @method('put')
    <div class="mb-3">
        <label for="name" class="form-label">Permission name</label>
        <input type="text" class="form-control" name="name" value="{{ $permission->name }}">
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>

@endsection
