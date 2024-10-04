@extends('layouts.admin')
@section('content')
    <div class="flex flex-col">
        <table class="shadow-2xl border-2 border-cyan-200 w-full mt-2">
            <thead class="text-center">
                <tr>
                    <th class="py-3 bg-[#5b8fc4] border-2 ">S.No</th>
                    <th class="py-3 bg-[#5b8fc4] border-2">UserId</th>
                    <th class="py-3 bg-[#5b8fc4] border-2">Activity</th>
                    <th class="py-3 bg-[#5b8fc4] border-2">Url</th>
                    <th class="py-3 bg-[#5b8fc4] border-2">Create At</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach($dashboards as $dashboard)
                <tr class="cursor-pointer">
                    <td class="py-3 px-6">{{$dashboard->id}}</td>
                    <td class="py-3 px-6">{{$dashboard->user_id}}</td>
                    <td class="py-3 px-6">{{$dashboard->activity}}</td>
                    <td class="py-3 px-6">
                        @if($dashboard->url)
                            <a href="{{ $dashboard->url }}">See detail</a>
                        @else
                            <p></p>
                        @endif
                    </td>
                    <td class="py-3 px-6">{{$dashboard->created_at}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-10">
            {{ $dashboards->links() }}
        </div>
    </div>
@endsection
