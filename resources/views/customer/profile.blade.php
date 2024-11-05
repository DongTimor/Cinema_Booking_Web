@extends('layouts.customer')

@section('content')
<div class="container w-50">
    <form method="post" action="/customers/profile/{{ $customer->id }}" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="mb-3 flex items-center justify-center">
            <label class="control-label" for="image"></label>
                <div class="avatar-img d-flex items-center justify-center flex-column">
                    <img class="rounded-circle img-fluid w-25 h-25 my-2" id="image-preview" src="{{ $customer->image ? asset($customer->image) : asset('/images/default.png') }}" alt="image" onclick="triggerUpload()" style="cursor: pointer;">
                    <input id="image-input" type="file" name="image" accept="image/*" class="form-control-file" style="{{ $customer->image ? 'display: none;' : '' }}">
                </div>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Full name</label>
            <input type="text" class="form-control" name="name" value="{{ $customer->name }}">
        @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" class="form-control" name="email" value="{{ $customer->email }}" disabled>
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
            <label for="gender" class="form-label">Gender</label>
            <select class="form-select" name="gender" value="{{ $customer->gender }}">
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="none">None</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mb-3">Update</button>
    </form>
</div>
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
