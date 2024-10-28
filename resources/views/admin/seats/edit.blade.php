@extends('adminlte::page')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Edit Seat</div>
                    <div class="card-body">
                        <form action="{{ route('seats.update', $seat->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="seat_number">Seat Number</label>
                                <input type="text" class="form-control" id="seat_number" name="seat_number"
                                    value="{{ $seat->seat_number }}">
                            </div>
                            <div class="form-group" style="display: flex; justify-content: space-between;">
                                <x-adminlte-button label="Back" theme="dark" icon="fas fa-arrow-left" onclick="window.location.href='{{ route('seats.index') }}'" />
                                <x-adminlte-button type="submit" label="Update" theme="success" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
