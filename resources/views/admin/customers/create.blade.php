@extends('layouts.admin')

@section('content')
<div class="container">
    <form  class="h-auto overflow-y:auto" method="post" action="/admin/customers/create" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
          <label for="name" class="form-label">Customer Name</label>
          <input type="text" class="form-control" name="name" >
        @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
        </div>
        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" class="form-control" name="phone_number" >
          @if ($errors->has('phone_number'))
              <span class="text-danger">{{ $errors->first('phone_number') }}</span>
          @endif
          </div>
          <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" name="address" >
          @if ($errors->has('address'))
              <span class="text-danger">{{ $errors->first('address') }}</span>
          @endif
          </div>
          <div>
            <label for="gender" class="form-label">Gender</label>
            <select class="form-select" name="gender">
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="none">None</option>
            </select>
          </div>
          <div>
            <label for="birth_date" class="form-label">Birth Date</label>
            <input type="date" class="form-control" name="birth_date" >
          @if ($errors->has('birth_date'))
              <span class="text-danger">{{ $errors->first('birth_date') }}</span>
          @endif
          </div>
          <div>
            <label for="roles" class="form-label">Roles</label>
            <x-adminlte-select name="roles[]" class='select2' multiple>
                @foreach ($roles as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </x-adminlte-select>
        </div>
          <div>
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" >
          @if ($errors->has('email'))
              <span class="text-danger">{{ $errors->first('email') }}</span>
          @endif
          </div>
          <div>
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" >
          @if ($errors->has('password'))
              <span class="text-danger">{{ $errors->first('password') }}</span>
          @endif
          </div>
          <div style="height: max-content">
            <label class="control-label" for="image">Image</label>
                <div class="avatar-img d-flex flex-column">
                    <img class="rounded-circle img-fluid w-20 my-2" id="image-preview" src="{{ isset($customer) ? asset($customer->image) : asset('default-image.png') }}" alt="image">
                    <input id="image-input" type="file" name="image" accept="image/*" class="form-control-file">
                </div>
          </div>
        <button type="submit" class="btn btn-primary">Create</button>
      </form>
</div>

@endsection
@section('scripts')
    <script src="{{ asset('js/uploadajax.js') }}"></script>
@endsection
