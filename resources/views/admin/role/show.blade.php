@extends('layouts.admin')

@section('content')

<form method="post" action="/admin/roles/{{ $role->id }}">
    @csrf
    @method('put')
    <div class="mb-3">
        <label for="name" class="form-label">Role name</label>
        <input type="text" class="form-control" name="name" value="{{ $role->name }}">
    </div>

    <x-adminlte-select name="permissions[]" class='select2' multiple>
        @foreach ($permissions as $item)
        <option value="{{ $item->id }}" {{ in_array($item->id, $ids) ? 'selected' : '' }}>
            {{ $item->name }}
        </option>
        @endforeach
    </x-adminlte-select>

    <button type="submit" class="btn btn-primary">Update</button>
</form>

@endsection
