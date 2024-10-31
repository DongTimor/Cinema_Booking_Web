@extends('layouts.admin')
@section('content')
    <div class="flex flex-col">
        <div class="flex justify-end mt-10">
            <a href="{{ route('auditoriums.create') }}">
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
                    <th class="py-3 bg-[#5b8fc4] border-2">Seat</th>
                    <th class="py-3 bg-[#5b8fc4] border-2">Status</th>
                    <th class="py-3 bg-[#5b8fc4] border-2">Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($auditoriums as $auditorium)
                    <tr class="cursor-pointer">
                        <td class="px-6">{{ $auditorium->id }}</td>
                        <td class="px-6">{{ $auditorium->name }}</td>
                        <td class="px-6">0/{{ $auditorium->total }}</td>
                        <td>
                            <div class="flex gap-3 justify-center items-center">
                                <div class="w-[10px] h-[10px] rounded-full bg-yellow-500"></div>
                                <p class="mt-3">Maintaince</p>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('auditoriums.edit', $auditorium->id) }}"
                                    class="no-underline font-bold">Edit</a>
                                <form action="{{ route('auditoriums.destroy', $auditorium->id) }}" method="POST">
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
            {{ $auditoriums->links() }}
        </div>
    </div>
@endsection
