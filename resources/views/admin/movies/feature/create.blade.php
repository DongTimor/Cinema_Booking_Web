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
            <div class="dropzone" id="posterDropzone" style="border: 2px dashed #007bff; padding: 20px; margin-top: 15px;">
                <h4 class="text-center">Upload Poster Image</h4>
                <div class="dz-message text-center">
                    <strong>Drop poster file here or click to upload.</strong>
                </div>
            </div>
            <div class="dropzone" id="bannerDropzone" style="border: 2px dashed #007bff; padding: 20px; margin-top: 15px;">
                <h4 class="text-center">Upload Banner Image</h4>
                <div class="dz-message text-center">
                    <strong>Drop banner file here or click to upload.</strong>
                </div>
            </div>
            <input type="hidden" name="poster_urls" id="posterUrls" value="">
            <input type="hidden" name="banner_urls" id="bannerUrls" value="">
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
        var posterUrls = [];
        var bannerUrls = [];
        var posterDropzone = new Dropzone("#posterDropzone", {
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
                myDropzone.on("sending", function(file, xhr, formData) {
                    formData.append("type", "poster");
                });
                myDropzone.on("success", function(file, response) {
                    posterUrls.push(response.url);
                    document.getElementById('posterUrls').value = posterUrls.join(',');
                    console.log('Poster URLs:', posterUrls); // Log the updated list of poster URLs
                });
                myDropzone.on("error", function(file, response) {
                    console.error(response);
                    alert("An error occurred while uploading the file: " + response);
                });
            }
        });

        var bannerDropzone = new Dropzone("#bannerDropzone", {
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
                myDropzone.on("sending", function(file, xhr, formData) {
                    formData.append("type", "banner");
                });
                myDropzone.on("success", function(file, response) {
                    bannerUrls.push(response.url);
                    document.getElementById('bannerUrls').value = bannerUrls.join(',');
                    console.log('Banner URLs:', bannerUrls); // Log the updated list of banner URLs
                });
                myDropzone.on("error", function(file, response) {
                    console.error(response);
                    alert("An error occurred while uploading the file: " + response);
                });
            }
        });
        document.getElementById("submitMovieForm").addEventListener("click", function(event) {
            event.preventDefault();
            var posterPromise = new Promise(function(resolve, reject) {
                if (posterDropzone.getQueuedFiles().length > 0) {
                    posterDropzone.on("queuecomplete", function() {
                        resolve();
                    });
                    posterDropzone.processQueue();
                } else {
                    resolve();
                }
            });
            var bannerPromise = new Promise(function(resolve, reject) {
                if (bannerDropzone.getQueuedFiles().length > 0) {
                    bannerDropzone.on("queuecomplete", function() {
                        resolve();
                    });
                    bannerDropzone.processQueue();
                } else {
                    resolve();
                }
            });
            Promise.all([posterPromise, bannerPromise]).then(function() {
                document.getElementById("movieForm").submit();
            });
        });
    </script>
@endsection
