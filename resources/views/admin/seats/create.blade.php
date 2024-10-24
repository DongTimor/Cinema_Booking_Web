@extends('layouts.admin')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/seats/create.css') }}">
@endsection
@section('content')
    <div id="loader" class="loader">
        <div class="loader-wheel"></div>
        <div class="loader-text"></div>
    </div>
    <div class="container" style="padding-top: 20px;">
        <div class="mb-3">
            <x-adminlte-select name="auditorium_id" id="auditorium_id" label="Auditorium">
                <option value="">--Select Auditorium--</option>
                @foreach ($auditoriums as $auditorium)
                    <option value="{{ $auditorium->id }}">{{ $auditorium->name }}</option>
                @endforeach
            </x-adminlte-select>
        </div>
        <div style="display: flex; width: 100%; gap: 20px;">
            <x-adminlte-input id="Total_seats" value="0" name="Total_seats" label="Total Seats" type="text"
                disabled>
                <x-slot name="prependSlot">
                    <div class="input-group-text text-purple">
                        <i class="fa fa-th-list"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
            <x-adminlte-input id="Row_number" name="Row_number" label="Row Number" type="number" min="1">
                <x-slot name="prependSlot">
                    <div class="input-group-text text-purple">
                        <i class="fa fa-table"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
            <x-adminlte-input id="Column_number" name="Column_number" label="Column Number" type="number" min="1">
                <x-slot name="prependSlot">
                    <div class="input-group-text text-purple">
                        <i class="fa fa-table"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
            <x-adminlte-input id="Available_seats" value="0/0" name="Available_seats" label="Available Seats"
                type="text" disabled>
                <x-slot name="prependSlot">
                    <div class="input-group-text text-purple">
                        <i class="fa fa-check-circle"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
        </div>
        <div style="display: flex; width: 100%; gap: 20px;">
            <x-adminlte-select id="Row_Regex_Select" name="Row_Regex_Select" label="Row Regex">
                <option value="A">A,B,C,...</option>
                <option value="B">a,b,c,...</option>
                <option value="C">1,2,3,...</option>
                <option value="D">Custom</option>
            </x-adminlte-select>
            <x-adminlte-select id="Column_Regex_Select" name="Column_Regex_Select" label="Column Regex">
                <option value="A">A,B,C,...</option>
                <option value="B">a,b,c,...</option>
                <option value="C">1,2,3,...</option>
                <option value="D">Custom</option>
            </x-adminlte-select>
        </div>
        <x-adminlte-input id="Row_Regex" value="Row_Regex" name="Row_Regex" label="Custom Row Regex" disabled>
            <x-slot name="prependSlot">
                <div class="input-group-text text-purple">
                    <i class="fa fa-list-ol"></i>
                </div>
            </x-slot>
            <x-slot name="bottomSlot">
                <span class="text-sm text-gray">
                    [Please choose Row Regex Select to set Row Regex, or set Custom to set your own Regex :
                    Row1,Row2,Row3,...]
                </span>
            </x-slot>
        </x-adminlte-input>
        <x-adminlte-input id="Column_Regex" value="Column_Regex" name="Column_Regex" label="Custom Column Regex" disabled>
            <x-slot name="prependSlot">
                <div class="input-group-text text-purple">
                    <i class="fa fa-list-ol"></i>
                </div>
            </x-slot>
            <x-slot name="bottomSlot">
                <span class="text-sm text-gray">
                    [Please choose Column Regex Select to set Column Regex, or set Custom to set your own Regex :
                    Column1,Column2,Column3,...]
                </span>
            </x-slot>
        </x-adminlte-input>
        <x-adminlte-textarea id="Demo" name="Demo" label="Demo" rows=5 label-class="text-warning" igroup-size="sm"
            placeholder="Demo results..." disabled>
            <x-slot name="prependSlot">
                <div class="input-group-text bg-dark">
                    <i class="fas fa-lg fa-file-alt text-warning"></i>
                </div>
            </x-slot>
        </x-adminlte-textarea>
        <button type="button" onclick="create()" class="btn btn-primary">Create</button>
        <button type="button" onclick="singleCreate()" class="btn btn-success">Single Create</button>
    </div>
    <div class="modal fade" id="customWeekModal" tabindex="-1" role="dialog" aria-labelledby="customWeekModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customWeekModalLabel">Completed generating seats</h5>
                </div>
                <div class="modal-body">
                    <p id="successCount">Success: 0 seats</p>
                    <p id="errorCount">Error: 0 seats</p>
                    <ul id="successList"></ul>
                    <ul id="errorList"></ul>
                </div>
                <div class="modal-footer">
                    <x-adminlte-button id="deleteButton" label="Close" theme="danger" onclick="closeModal()" />
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/seats/create.js') }}"></script>
@endsection
