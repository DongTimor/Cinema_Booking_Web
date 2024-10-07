@extends('layouts.admin')
@section('styles')
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css"
        rel="stylesheet">
@endsection
@section('content')
    <div class="">
        <h1>Create Movie</h1>
        <form action="{{ route('movies.features.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display: flex; flex-direction: row; gap: 30px;">
                <x-adminlte-input id="name" name="name" label="Name*" fgroup-class="w-100" value="{{ old('name') }}" />
                <x-adminlte-input type="number" name="duration" label="Duration (minutes)*" fgroup-class="w-30" value="{{ old('duration') }}" />
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
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </x-adminlte-select>
            </div>
            <x-adminlte-input-file id="images" name="image_id[]" label="Upload files" value="{{ old('image_id') }}"
                placeholder="Choose multiple files..." igroup-size="lg" legend="Choose" multiple accept="image/jpeg, image/png, image/jpg">
                <x-slot name="prependSlot">
                    <div class="input-group-text text-primary">
                        <i class="fas fa-file-upload"></i>
                    </div>
                </x-slot>
            </x-adminlte-input-file>
            <x-adminlte-input name="trailer" label="Trailer" value="{{ old('trailer') }}" />
            <x-adminlte-textarea name="description" label="Description" rows=6 igroup-size="sm"
                placeholder="Insert description..." value="{{ old('description') }}">
                <x-slot name="prependSlot">
                    <div class="input-group-text bg-dark">
                        <i class="fas fa-lg fa-file-alt text-warning"></i>
                    </div>
                </x-slot>
            </x-adminlte-textarea>
            <x-adminlte-button type="submit" label="Create" theme="primary"
                class="bg-primary text-white hover:bg-secondary" />
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
        $(function() {
            $('#starttimepicker').datetimepicker({
                format: 'MM/DD/YYYY'
            });
            $('#endtimepicker').datetimepicker({
                format: 'MM/DD/YYYY'
            });
        });
    </script>
@endsection
