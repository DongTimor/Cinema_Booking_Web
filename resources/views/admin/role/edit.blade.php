{{-- @extends('layouts.admin')

@section('content')

<form method="post" action="/admin/roles/{{ $role->id }}">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Role name</label>
        <input type="text" class="form-control" name="name" value="{{ $role->name }}">
    </div>

    <x-adminlte-select name="permissions[]" class='select2' multiple disabled>
        @foreach ($permissions as $item)
        <option value="{{ $item->id }}" {{ $item->name ? 'selected' : '' }}>
            {{ $item->name }}
        </option>
        @endforeach
    </x-adminlte-select>

    <button type="submit" class="btn btn-primary">Update</button>
</form>

@endsection --}}
