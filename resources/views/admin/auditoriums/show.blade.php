@extends('layouts.admin')
@section('content')
    <div class="flex flex-col items-center justify-center">
      <h1>Name : {{$auditorium->name}}</h1>
      <h1>Total Seats : {{$auditorium->total}}</h1>
    </div>
@endsection