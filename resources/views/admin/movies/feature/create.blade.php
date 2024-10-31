@extends('layouts.admin')

@section('styles')
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
@endsection

@section('content')
    <div class="">
        <h1>Create Movie</h1>
        <form id="movieForm" action="{{ route('movies.features.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display: flex; flex-direction: row; gap: 30px;">
                <x-adminlte-input id="name" name="name" label="Name*" fgroup-class="w-100"
                    value="{{ old('name') }}" />
                <x-adminlte-input type="number" name="duration" label="Duration (minutes)*" fgroup-class="w-30"
                    value="{{ old('duration') }}" />
                    <x-adminlte-input type="text" name="price" label="Price (VND)*" fgroup-class="w-30"
                    value="{{ old('price') }}" />
            </div>
            <div class="form-group d-flex justify-content-between" style="width: 100% !important; gap: 50px">
                <div class="form-group d-flex flex-column justify-content-between">
                    <label for="datetimepicker">Select Start Date*</label>
                    <div class="input-group date" id="starttimepicker" style="width: max-content !important;"
                        data-target-input="nearest">
                        <input id="start_date" name="start_date" type="text" class="form-control datetimepicker-input"
                            data-target="#starttimepicker" value="{{ old('start_date') }}" />
                        <div class="input-group-append" data-target="#starttimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                        @error('start_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group d-flex flex-column justify-content-between">
                    <label for="datetimepicker">Select End Date*</label>
                    <div class="input-group date" id="endtimepicker" style="width: max-content !important;"
                        data-target-input="nearest">
                        <input id="end_date" name="end_date" type="text" class="form-control datetimepicker-input"
                            data-target="#endtimepicker" value="{{ old('end_date') }}" />
                        <div class="input-group-append" data-target="#endtimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                        @error('end_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <x-adminlte-select class="select2" name="category_id[]" label="Categories*" fgroup-class="w-100" multiple>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </x-adminlte-select>
            </div>

            <!-- Dropzone -->
            <div class="dropzone" id="imageDropzone" style="border: 2px dashed #007bff; padding: 20px; margin-top: 15px;">
                <h4 class="text-center">Upload Images</h4>
                <div class="dz-message text-center">
                    <strong>Drop files here or click to upload.</strong>
                </div>
            </div>
            <div id="imagePreview" class="d-flex flex-wrap" style="margin-top: 15px;"></div>
            <input type="hidden" name="image_urls" id="imageUrls" value="">
            <div class="mt-3">
                <x-adminlte-input name="trailer" label="Trailer" value="{{ old('trailer') }}" />
            </div>

            <x-adminlte-textarea name="description" label="Description" rows=6 igroup-size="sm"
                placeholder="Insert description...">{{ old('description') }}</x-adminlte-textarea>

            <x-adminlte-button type="submit" id="submitMovieForm" label="Create" theme="primary"
                class="bg-primary text-white hover:bg-secondary" />
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#starttimepicker').datetimepicker({
                format: 'MM/DD/YYYY'
            });
            $('#endtimepicker').datetimepicker({
                format: 'MM/DD/YYYY'
            });
        });

        Dropzone.autoDiscover = false; 
        var movieDropzone = new Dropzone("#imageDropzone", {
            url: "{{ route('movies.features.uploadImages') }}",
            maxFilesize: 2, 
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            autoProcessQueue: false,
            parallelUploads: 5,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            init: function() {
                var myDropzone = this;
                document.getElementById("submitMovieForm").addEventListener("click", function(event) {
                    event.preventDefault();
                    if (myDropzone.getQueuedFiles().length > 0) {
                        myDropzone.processQueue();
                    } else {
                        document.getElementById("movieForm").submit();
                    }
                });
                myDropzone.on("success", function(file, response) {
                    var imageUrls = document.getElementById('imageUrls').value;
                    imageUrls = imageUrls ? imageUrls.split(',') : [];
                    imageUrls.push(response.url);
                    document.getElementById('imageUrls').value = imageUrls.join(',');
                    console.log("File uploaded successfully: ", response);
                });
                myDropzone.on("queuecomplete", function() {
                    document.getElementById("movieForm").submit();
                });
                myDropzone.on("error", function(file, response) {
                    console.error(response);
                    alert("An error occurred while uploading the file: " + response);
                });
            }
        });
    </script>
@endsection
