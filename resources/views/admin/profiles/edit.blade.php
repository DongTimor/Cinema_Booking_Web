@extends('layouts.admin')

@section('content')

<form method="post" action="/admin/profile/{{ $profile->user->id }}" enctype="multipart/form-data">
    @csrf
    @method('put')
    <div class="justify-content-center flex">
        <img class="rounded-circle w-25 my-3 border cursor-pointer" id="image-preview"
            src="{{ $profile->image ? asset($profile->image) : asset("images/default.jpg") }}" alt="image"
            onclick="triggerUpload()">
        <input class="hidden" id="image-input" type="file" name="image"
            accept="image/*">
    </div>
    <div class="mb-3">
        <label for="name" class="form-label">Full name</label>
        <input type="text" class="form-control" name="name" value="{{ $profile->user->name }}" disabled>
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone number</label>
        <input type="number" class="form-control" name="phone" value="{{ $profile->phone }}">
    @if ($errors->has('phone'))
        <span class="text-danger">{{ $errors->first('phone') }}</span>
    @endif
    </div>
    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <input type="text" class="form-control" name="address" value="{{ $profile->address }}">
    @if ($errors->has('address'))
        <span class="text-danger">{{ $errors->first('address') }}</span>
    @endif
    </div>
    <div class="mb-3">
        <label for="birth_date" class="form-label">Birth date</label>
        <input type="date" class="form-control" name="birth_date" value="{{ $profile->birth_date }}">
    @if ($errors->has('birth_date'))
        <span class="text-danger">{{ $errors->first('birth_date') }}</span>
    @endif
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
