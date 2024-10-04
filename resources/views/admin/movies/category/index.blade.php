@extends('layouts.admin')
@section('content')
    <div class="flex flex-col">
        <div class="flex justify-end mt-10">
            <a class="no-underline" href="{{route('movies.categories.create')}}">
                <div
                    class="w-14 h-14 rounded-full group hover:text-white hover:font-bold bg-[#78838f] flex items-center justify-center p-3 ease-in duration-300 cursor-pointer">
                    <h1 class="group-hover:text-[50px]">+</h1>
                </div>
            </a>

        </div>
        {{-- <x-create-button /> --}}
        <table class="shadow-2xl border-2 border-cyan-200 w-full mt-2 table-fixed">
            <thead class="text-center">
                <tr>
                    <th class="py-3 bg-[#5b8fc4] border-2 w-1/5">S.No</th>
                    <th class="py-3 bg-[#5b8fc4] border-2 w-3/5">Name</th>
                    <th class="py-3 bg-[#5b8fc4] border-2 w-1/5">Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($categories as $category)
                <tr class="cursor-pointer">
                    <td class="py-3">{{$loop->iteration}}</td>
                    <td class="py-3">{{$category->name}}</td>
                    <td class="py-3 flex justify-center items-center relative">
                        <div class="absolute flex justify-center mt-4 gap-3">
                            <a href="{{route('movies.categories.edit',$category->id)}}" class="no-underline font-bold">Edit</a>
                            <form action="{{route('movies.categories.destroy',$category->id)}}" method="POST" class="">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 font-bold">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-10">
            {{ $categories->links() }}
        </div>
    </div>
@endsection
