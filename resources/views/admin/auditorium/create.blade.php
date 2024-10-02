@extends('layouts.admin')
@section('content')
    <div class="flex flex-col items-center justify-center">
      <h1>Create Auditorium</h1>
      <form action="{{ route('auditoriums.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
            <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="total" class="block text-gray-700 text-sm font-bold mb-2">Seats:</label>
            <input type="number" name="total" id="seats" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Create
            </button>
        </div>
    </form>
    </div>
@endsection