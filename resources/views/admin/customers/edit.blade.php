@extends('layouts.admin')

@section('content')

<form method="post" action="/admin/customers/{{ $customer->id }}" enctype="multipart/form-data">
    @csrf
    @method('put')
    <div class="mb-3">
        <label for="name" class="form-label">Customer name</label>
        <input type="text" class="form-control" name="name" value="{{ $customer->name }}" disabled>
    </div>
    <div class="mb-3">
        <label for="phone_number" class="form-label">Phone number</label>
        <input type="number" class="form-control" name="phone_number" value="{{ $customer->phone_number }}">
    @if ($errors->has('phone_number'))
        <span class="text-danger">{{ $errors->first('phone_number') }}</span>
    @endif
    </div>
    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <input type="text" class="form-control" name="address" value="{{ $customer->address }}">
    @if ($errors->has('address'))
        <span class="text-danger">{{ $errors->first('address') }}</span>
    @endif
    </div>
    <div class="mb-3">
        <label for="date_of_birth" class="form-label">Birth date</label>
        <input type="date" class="form-control" name="date_of_birth" value="{{ $customer->date_of_birth }}">
    @if ($errors->has('date_of_birth'))
        <span class="text-danger">{{ $errors->first('date_of_birth') }}</span>
    @endif
    </div>
    <div>
        <label for="gender" class="form-label">Gender</label>
        <select class="form-select" name="gender" value="{{ $customer->gender }}">
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="none">None</option>
        </select>
    </div>
    <div>
        <label class="control-label" for="image">Image</label>
            <div class="avatar-img d-flex flex-column">
                <img class="rounded-circle img-fluid w-20 my-2" id="image-preview" src="{{ asset($profile->image) }}" alt="image">
                <input id="image-input" type="file" name="image" accept="image/*" class="form-control-file">
            </div>
    </div>
    <button type="submit" class="btn btn-primary mt-2">Update</button>
</form>

@endsection

@section('scripts')
    <script src="{{ asset('js/uploadajax.js') }}"></script>
@endsection
