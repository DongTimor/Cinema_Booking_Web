@extends('layouts.admin')

@section('content')

<form method="post" action="/admin/customers/{{ $customer->id }}" enctype="multipart/form-data">
    @csrf
    @method('put')
    <div class="justify-content-center flex">
        <img class="rounded-circle w-25 my-3 border cursor-pointer" id="image-preview"
            src="{{ $customer->image ? asset($customer->image) : asset("images/default.jpg") }}" alt="image"
            onclick="triggerUpload()">
        <input class="hidden" id="image-input" type="file" name="image"
            accept="image/*">
    </div>
    <div class="mb-3">
        <label for="name" class="form-label">Customer name</label>
        <input type="text" class="form-control" name="name" value="{{ $customer->name }}">
        @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Phone number</label>
        <input type="email" class="form-control" name="email" value="{{ $customer->email }}">
    @if ($errors->has('email'))
        <span class="text-danger">{{ $errors->first('email') }}</span>
    @endif
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
        <label for="birth_date" class="form-label">Birth date</label>
        <input type="date" class="form-control" name="birth_date" value="{{ $customer->birth_date }}">
    @if ($errors->has('birth_date'))
        <span class="text-danger">{{ $errors->first('birth_date') }}</span>
    @endif
    </div>
    <div class="mb-3">
        <x-adminlte-select name="roles[]" class='select2' multiple>
            @foreach ($roles as $item)
                <option value="{{ $item->id }}" {{ in_array($item->id, $ids) ? 'selected' : '' }}>
                    {{ $item->name }}
                </option>
            @endforeach
        </x-adminlte-select>
    </div>
    <div class="mb-3">
        <label for="gender" class="form-label">Gender</label>
        <select class="form-select" name="gender">
            <option value="male" {{ $customer->gender == 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ $customer->gender == 'female' ? 'selected' : '' }}>Female</option>
            <option value="none" {{ $customer->gender == 'none' ? 'selected' : '' }}>None</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary mb-3">Update</button>
</form>

@endsection

@section('scripts')
    <script src="{{ asset('js/uploadajax.js') }}"></script>
    <script>
        function triggerUpload() {
            document.getElementById('image-input').click();
        }
        $(document).on("change", "#image-input", function() {
            let file = this.files[0];
            let reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
            this.style.display = 'none';
        });
    </script>
@endsection
