@extends('layouts.app')

@section('content')
    <div class="flex flex-col">
        <div class="w-full h-[500px] bg-black flex items-center justify-center">
            <img class="w-[900px] h-full"
                src="{{ $movie->images->skip(1)->first()?->url ? asset($movie->images->skip(1)->first()->url) : asset('default-image.jpg') }}" />
        </div>
        <div class="flex flex-col gap-4 -mt-10 z-10 ml-72 w-2/4">
            <div class="flex gap-4">
                <img class="w-[280px] h-[400px] border-3 border-white rounded-md"
                    src="{{ $movie->images->first()?->url ? asset($movie->images->first()->url) : asset('default-image.jpg') }}" />
                <div class="flex flex-col gap-3 justify-end">
                    <h1 class="text-3xl font-extrabold">{{ $movie->name }}</h1>
                    <div class="flex gap-4">
                        <div class="flex gap-2 items-center">
                            <i class="far fa-clock text-[#f8c92c] text-xl"></i>
                            <p>{{ $movie->duration }} minutes</p>
                        </div>
                        <div class="flex gap-2 items-center justify-center">
                            <i class="far fa-calendar text-[#f8c92c] text-xl"></i>
                            <p>{{ $movie->start_date }}</p>
                        </div>
                    </div>
                    <p>Category:
                        @foreach ($movie->categories as $category)
                            {{ $category->name }}@if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </p>
                    <p>Director : Updating...</p>
                    <p>Characters : Updating...</p>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <div class="flex gap-2">
                    <div class="w-[5px] h-[25px] bg-[#f8c92c]"></div>
                    <h1 class="text-xl font-extrabold">Description</h1>
                </div>
                <p class="text-base">{{ $movie->description }}</p>
            </div>
            <div class="flex flex-col gap-2">
                <div class="flex gap-2">
                    <div class="w-[5px] h-[25px] bg-[#f8c92c]"></div>
                    <h1 class="text-xl font-extrabold">Schedule</h1>
                </div>
            </div>
            <form action="{{route('momo-payment')}}" method="POST">
                @csrf
                <button type="submit" name="payUrl">CheckOut with MOMO</button>
            </form>
        </div>
    </div>
@endsection
