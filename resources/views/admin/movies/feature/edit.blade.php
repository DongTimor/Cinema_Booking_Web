@extends('layouts.admin')
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
@endsection
@section('content')
    <h1>Edit Movie</h1>
    <form action="{{ route('movies.features.update', $movie->id) }}" method="POST" enctype="multipart/form-data"
        id="update_form">
        @csrf
        @method('PUT')
        <div class="flex gap-2">
            <x-adminlte-input id="name" name="name" label="Name*" fgroup-class="w-75" value="{{ $movie->name }}" />
            <x-adminlte-input type="number" name="duration" label="Duration (minutes)*" value="{{ $movie->duration }}" />
            <x-adminlte-input type="text" name="price" label="Price (VND)*" fgroup-class="w-25"
                value="{{ $movie->price }}" />
        </div>
        <div class="flex gap-3">
            <div class="start-date w-1/5">
                <label>Select Start Date*</label>
                <div class="input-group">
                    <input name="start_date" type="text" class="form-control datepicker shadow-none"
                        value="{{ $movie->start_date }}" />
                    <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                    @error('start_date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="end-date w-1/5">
                <label>Select End Date*</label>
                <div class="input-group">
                    <input name="end_date" type="text" class="form-control datepicker shadow-none"
                        value="{{ $movie->end_date }}" />
                    <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                    @error('end_date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <x-adminlte-select class="select2" name="category_id[]" label="Categories*" fgroup-class="w-75" multiple>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ in_array($category->id, $movie->categories->pluck('id')->toArray()) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </x-adminlte-select>
        </div>
        <div class="dropzone my-3" id="posterDropzone" style="border: 2px dashed #007bff; padding: 20px; margin-top: 15px;">
            <h4>Upload Posters</h4>
            @foreach ($posters as $poster)
                <div class="dz-preview dz-image-preview">
                    <img class="dz-image rounded-md" src="{{ asset('storage/' . $poster->url) }}" alt="Poster">
                    <input type="hidden" name="poster_urls[]" value="{{ $poster->url }}">
                    <a class="dz-remove remove-btn">Remove file</a>
                </div>
            @endforeach
        </div>
        <div class="dropzone my-3" id="bannerDropzone" style="border: 2px dashed #007bff; padding: 20px; margin-top: 15px;">
            <h4>Upload Banners</h4>
            @foreach ($banners as $banner)
                <div class="dz-preview dz-image-preview">
                    <img class="dz-image rounded-md" src="{{ asset('storage/' . $banner->url) }}" alt="Banner">
                    <input type="hidden" name="banner_urls[]" value="{{ $banner->url }}">
                    <a class="dz-remove remove-btn">Remove file</a>
                </div>
            @endforeach
        </div>
        <x-adminlte-input name="trailer" label="Trailer" value="{{ $movie->trailer }}" />
        <x-adminlte-textarea name="description" label="Description" rows=6 igroup-size="sm"
            placeholder="Insert description...">
            <x-slot name="prependSlot">
                <div class="input-group-text bg-dark">
                    <i class="fas fa-lg fa-file-alt text-warning"></i>
                </div>
            </x-slot>
            {{ $movie->description }}
        </x-adminlte-textarea>
        <button type="submit" class="btn btn-outline-primary mb-3">Update</button>
    </form>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script>
        $('.datepicker').each(function() {
            $(this).flatpickr({
                dateFormat: 'd/m/Y',
            });
        })

        Dropzone.autoDiscover = false;

        const posterDropzone = new Dropzone("#posterDropzone", {
            url: "/admin/movies/features/upload-images",
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            sending: function(file, xhr, formData) {
                formData.append("type", "poster");
            },
            success: function(file, response) {
                console.log(response);
                let hiddenInput = document.createElement("input");
                hiddenInput.type = "hidden";
                hiddenInput.name = "poster_urls[]";
                hiddenInput.value = response.url;
                file.previewElement.appendChild(hiddenInput);
            }
        });

        const bannerDropzone = new Dropzone("#bannerDropzone", {
            url: "/admin/movies/features/upload-images",
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            sending: function(file, xhr, formData) {
                formData.append("type", "banner");
            },
            success: function(file, response) {
                console.log(response);
                let hiddenInput = document.createElement("input");
                hiddenInput.type = "hidden";
                hiddenInput.name = "banner_urls[]";
                hiddenInput.value = response.url;
                file.previewElement.appendChild(hiddenInput);
            }
        });
        $('.remove-btn').on('click', function() {
            $(this).parent().remove();
        });
    </script>
@endsection
