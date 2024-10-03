@extends('layouts.admin')
@section('content')
    <div class="">
      <h1>Create Movie</h1>
      <form action="" method="POST" class="grid grid-cols-3 auto-rows-[100px] gap-5 w-full">
        @csrf
        <div class="">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
            <input type="text" name="name" id="name" class="w-full shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="">
            <label for="trailer" class="block text-gray-700 text-sm font-bold mb-2">Trailer:</label>
            <input type="text" name="trailer" id="trailer" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="">
            <label for="categories" class="block text-gray-700 text-sm font-bold mb-2">Categories:</label>
            <x-adminlte-select name="categories[]" class='select2' multiple>
                @foreach ($categories as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </x-adminlte-select>
        </div>
        <div class="">
            <label for="trailer" class="block text-gray-700 text-sm font-bold mb-2">Trailer:</label>
            <input type="text" name="trailer" id="trailer" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Create
            </button>
        </div>
    </form>
    </div>
@endsection