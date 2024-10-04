@extends('layouts.admin')
@section('content')
    <div class="flex flex-col">
        <div class="flex justify-end mt-10">
            <a href="{{route('auditoriums.create')}}">
                <div class="w-14 h-14 rounded-full group hover:text-white hover:font-bold bg-[#78838f] flex items-center justify-center p-3 ease-in duration-300 cursor-pointer">
                    <h1 class="group-hover:text-[50px]">+</h1>
                </div>
            </a>
        </div>
        <table class="shadow-2xl border-2 border-cyan-200 w-full mt-2">
            <thead class="text-center">
                <tr>
                    <th class="py-3 bg-[#5b8fc4] border-2 ">S.No</th>
                    <th class="py-3 bg-[#5b8fc4] border-2">Name</th>
                    <th class="py-3 bg-[#5b8fc4] border-2">Seat</th>
                    <th class="py-3 bg-[#5b8fc4] border-2">Status</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach($auditoriums as $auditorium)
                <tr class="cursor-pointer">
                    <td class="py-3 px-6">{{$auditorium->id}}</td>
                    <td class="py-3 px-6">{{ $auditorium->name }}</td>
                    <td class="py-3 px-6">0/{{$auditorium->total}}</td>
                    <td class="py-3 px-6 relative flex justify-center items-center">
                        <div class="w-[10px] h-[10px] rounded-full bg-yellow-500 absolute left-44 top-6"></div>
                        <p>Maintaince</p>
                        <a href="{{route('auditoriums.edit',$auditorium->id)}}" class="absolute right-24 top-4 no-underline font-bold">Edit</a>
                        <form action="{{ route('auditoriums.destroy', $auditorium->id) }}" method="POST" class="absolute right-7 top-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 font-bold">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-10">
            {{ $auditoriums->links() }}
        </div>
    </div>
@endsection
