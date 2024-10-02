@extends('layouts.admin')
@section('content')
    <div class="flex flex-col items-center justify-center">
      <h1 class="mt-10">Edit Auditorium</h1>
      <form action="{{ route('auditoriums.update',$auditorium->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
            <input type="text" name="name" value="{{$auditorium->name}}" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="total" class="block text-gray-700 text-sm font-bold mb-2">Seats:</label>
            <input type="number" name="total" value="{{$auditorium->total}}" id="seats" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update
            </button>
        </div>
    </form>
    </div>
@endsection