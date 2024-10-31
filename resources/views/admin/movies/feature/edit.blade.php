@extends('layouts.admin')
@section('styles')
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css"
        rel="stylesheet">
@endsection
@section('content')
    <div class="">
        <h1>Edit Movie</h1>
        <form action="{{ route('movies.features.update', $movie->id) }}" method="POST" enctype="multipart/form-data"
            id="update_form">
            @csrf
            @method('PUT')
            <div style="display: flex; flex-direction: row; gap: 30px;">
                <x-adminlte-input id="name" name="name" label="Name*" fgroup-class="w-100"
                    value="{{ $movie->name }}" />
                <x-adminlte-input type="number" name="duration" label="Duration (minutes)*" fgroup-class="w-30"
                    value="{{ $movie->duration }}" />
                <x-adminlte-input type="text" name="price" label="Price (VND)*" fgroup-class="w-30"
                    value="{{$movie->price}}" />
            </div>
            <div class="form-group d-flex justify-content-between" style="width: 100% !important; gap: 50px">
                <div class="form-group d-flex flex-column justify-content-between">
                    <label for="datetimepicker">Select Start Date*</label>
                    <div class="input-group date" id="starttimepicker" style="width: max-content !important;"
                        data-target-input="nearest">
                        <input id="start_date" name="start_date" type="text" class="form-control datetimepicker-input"
                            data-target="#starttimepicker" value="{{ $movie->start_date }}" />
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
                            data-target="#endtimepicker" value="{{ $movie->end_date }}" />
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
                        <option value="{{ $category->id }}"
                            {{ in_array($category->id, $movie->categories->pluck('id')->toArray()) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </x-adminlte-select>
            </div>
            <x-adminlte-input-file id="images" name="image_id[]" label="Upload files" value="{{ $movie->image_id }}"
                placeholder="Choose multiple files..." igroup-size="lg" legend="Choose" multiple
                accept="image/jpeg, image/png, image/jpg">
                <x-slot name="prependSlot">
                    <div class="input-group-text text-primary">
                        <i class="fas fa-file-upload"></i>
                    </div>
                </x-slot>
            </x-adminlte-input-file>
            <input type="hidden" name="image_urls" value="{{ implode(',', $movie->images->pluck('url')->toArray()) }}">
            {{-- <input type="file" name="image_arr[]" id="image_arr" class="hidden" value=""> --}}
            <div class="flex gap-4">
                @foreach ($images as $image)
                    <div class="relative">
                        <img class="w-[150px] h-[200px]" src="{{ asset($image->url) }}" alt="Movie Image">
                        <i id="delete-btn"
                            class="far fa-times-circle absolute -top-3 bg-red-500 text-white rounded-full -right-3 text-2xl cursor-pointer"></i>
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
            <x-adminlte-button type="submit" label="Update" theme="primary"
                class="bg-primary text-white hover:bg-secondary" />
            <div>
                <strong>Movie ID:</strong> {{ $movie->id }}
            </div>
    </div>
    </form>
    </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js">
    </script>
    <script type="text/javascript">
        let image_arr = [];

        function update_img_arr(event) {
            const target = event.currentTarget
            let files = target.files
            image_arr = [...image_arr, ...Array.from(files)]
        }
        $(function() {
            $('#starttimepicker').datetimepicker({
                format: 'MM/DD/YYYY'
            });
            $('#endtimepicker').datetimepicker({
                format: 'MM/DD/YYYY'
            });
            $('#update_form').on("submit", async function(event) {
                event.preventDefault();
                const form = event.currentTarget
                const formData = new FormData(form)
                image_arr.forEach(imageFIle => {
                    formData.append("image_arr[]", imageFIle, imageFIle.name)
                });
                await fetch(form.action, {
                    method: "POST",
                    body: formData,
                })
                window.location.href = "/admin/movies/features";
            })
            $('#images').change(
                update_img_arr
            );
            let new_image_arr = $('input[name="image_urls"]').val().split(',');
            $('.flex').on('click', '.fa-times-circle', function() {
                var imageUrl = $(this).siblings('img').attr('src');
                if ($(this).parent().attr('data-filename')) {
                    var imageName = $(this).parent().attr('data-filename');
                    new_image_arr = new_image_arr.filter(function(name) {
                        return name !== imageName;
                    });
                } else {
                    var imageName = imageUrl.split('/').pop();
                    new_image_arr = new_image_arr.filter(function(url) {
                        return !url.includes(imageName);
                    })
                };
                $(this).parent().remove();
                $('input[name="image_urls"]').val(new_image_arr.join(','));
            });
            $('#images').on('change', function(e) {
                var files = e.target.files;
                var imageContainer = $('.flex');
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    var imageUrl = URL.createObjectURL(file);
                    var imageHtml = `
                    <div class="relative" data-filename="${file.name}">
                        <img class="w-[150px] h-[200px]" src="${imageUrl}" alt="New Movie Image">
                        <i class="far fa-times-circle absolute -top-3 bg-red-500 text-white rounded-full -right-3 text-2xl cursor-pointer"></i>
                    </div>
                `;
                    imageContainer.append(imageHtml);
                    new_image_arr.push(file.name);
                }
                $('input[name="image_urls"]').val(new_image_arr.join(','));
            });
        });
    </script>
@endsection
