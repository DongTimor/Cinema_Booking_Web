@extends('layouts.admin')

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css"
        rel="stylesheet">
@endsection

@section('content')
    <div class="">
        <h1>Movie Details</h1>

        <div style="display: flex; flex-direction: row; gap: 30px;">
            <div class="w-100">
                <strong>Name:</strong>
                <p>{{ $movie->name }}</p>
            </div>
            <div class="w-30">
                <strong>Duration (minutes):</strong>
                <p>{{ $movie->duration }}</p>
            </div>
        </div>
        <div class="form-group d-flex justify-content-between" style="width: 100% !important; gap: 50px">
            <div>
                <strong>Start Date:</strong>
                <p>{{ \Carbon\Carbon::parse($movie->start_date)->format('m/d/Y') }}</p>
            </div>
            <div>
                <strong>End Date:</strong>
                <p>{{ \Carbon\Carbon::parse($movie->end_date)->format('m/d/Y') }}</p>
            </div>
            <div class="w-100">
                <strong>Categories:</strong>
                <ul>
                    @foreach ($movie->categories as $category)
                        <li>{{ $category->name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Display Images -->
        <div class="form-group">
            <strong>Images:</strong>
            <div class="d-flex flex-wrap">
                @foreach($movie->images as $image)
                    <div class="p-2">
                        <img src="{{ asset($image->url) }}" alt="Movie Image" style="max-width: 150px; height: auto;">
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <strong>Trailer:</strong>
            <p>{{ $movie->trailer }}</p>
        </div>

        <div class="form-group">
            <strong>Description:</strong>
            <p>{{ $movie->description }}</p>
        </div>

        <div class="mt-3">
            <a href="{{ route('movies.features.index') }}" class="btn btn-primary">Back to Movies</a>
        </div>
    </div>
@endsection
