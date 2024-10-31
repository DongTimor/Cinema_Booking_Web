@extends('layouts.admin')
@section('content')
    <div class="flex flex-col">
        <div class="flex justify-end mt-10">
            <a class="no-underline" href="{{ route('movies.features.create') }}">
                <div
                    class="w-14 h-14 rounded-full group hover:text-white hover:font-bold bg-[#78838f] flex items-center justify-center p-3 ease-in duration-300 cursor-pointer">
                    <h1 class="group-hover:text-[50px]">+</h1>
                </div>
            </a>
        </div>
        <table class="shadow-2xl border-2 border-cyan-200 w-full mt-2">
            <thead class="text-center">
                <tr>
                    <th class="py-3 bg-[#5b8fc4] border-2 ">S.No</th>
                    <th class="py-3 bg-[#5b8fc4] border-2">Name</th>
                    <th class="py-3 bg-[#5b8fc4] border-2">Duration</th>
                    <th class="py-3 bg-[#5b8fc4] border-2">Start Date</th>
                    <th class="py-3 bg-[#5b8fc4] border-2">End Date</th>
                    <th class="py-3 bg-[#5b8fc4] border-2">Status</th>
                    <th class="py-3 bg-[#5b8fc4] border-2">Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($movies as $movie)
                    <tr class="cursor-pointer">
                        <td class="px-6">{{ $movie->id }}</td>
                        <td class="px-6">{{ $movie->name }}</td>
                        <td class="px-6">{{ $movie->duration }}</td>
                        <td class="px-6">{{ $movie->start_date }}</td>
                        <td class="px-6">{{ $movie->end_date }}</td>
                        <td class="px-6">
                            <div class="flex gap-3 items-center justify-center">
                                <div class="w-[10px] h-[10px] rounded-full bg-yellow-500"></div>
                                <p class="mt-3">{{ $movie->status }}</p>
                            </div>
                        </td>
                        <td class="px-6">
                            <div class="flex gap-2 justify-center items-center">
                                <a href="{{ route('movies.features.edit', $movie->id) }}"
                                    class="no-underline font-bold">Edit</a>
                                <form action="{{ route('movies.features.destroy', $movie->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 font-bold mt-3">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-10">
            {{ $movies->links() }}
        </div>
    </div>
@endsection
