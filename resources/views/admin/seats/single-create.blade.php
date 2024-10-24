@extends('adminlte::page')
@section('content')
    <x-adminlte-card title="Create Seat" theme="dark" icon="fas fa-lg fa-chair">
            <div class="row">
                <div class="col-md-9">
                    <x-adminlte-select id="auditorium_id" name="auditorium_id" label="Auditorium ID" required>
                        <option value="">-Select Auditorium-</option>
                        @foreach ($auditoriums as $auditorium)
                            <option value="{{ $auditorium->id }}">{{ $auditorium->name }}</option>
                        @endforeach
                    </x-adminlte-select>
                </div>
                <div class="col-md-3">
                    <x-adminlte-input style="text-align: center;" disabled value="0/0" id="total_available_seats"
                        name="total_available_seats" label="Total Available Seats" required />
                </div>
            </div>
            <x-adminlte-input id="seat_number" name="seat_number" label="Seat Number" required />
            <x-adminlte-button type="button" theme="dark" icon="fas fa-plus" text="Create" onclick="create()" />
            <x-adminlte-button label="Automatically Create" type="button" theme="success" text="Automatically Create" onclick="autoCreate()" />
    </x-adminlte-card>
@endsection
@section('js')
    <script src="{{ asset('js/seats/single-create.js') }}"></script>
@endsection
