<div class="flex flex-col gap-2">
    <div class="h-fit group">
        <div class="relative overflow-hidden cursor-pointer">
            <img src="{{ $movie->images->first()?->url ? asset($movie->images->first()->url) : asset('default-image.jpg') }}"
                class="w-[290px] h-[435px] rounded-xl object-cover">
            <div
                class="absolute h-full w-full bg-black/40 flex items-center justify-center opacity-5 -bottom-20 group-hover:bottom-0 group-hover:opacity-100 rounded-2xl transition-all duration-300 ">
                <div class="flex flex-col gap-4 text-white">
                    <button class="border-2 text-[#f8c92c] py-2.5 px-4 rounded-md group-hover:bg-black font-bold"><a href="{{ route('detail',$movie->id) }}">Booking</a></button> 
                    <button class="border-2 py-2.5 px-4 rounded-md hover:bg-[#f8c92c] hover:text-black font-bold trailer-button" data-video-url="{{ $movie->trailer }}">Trailer</button> 
                </div>
            </div>
        </div>
    </div>
    <h1 class="font-bold text-xl break-words whitespace-normal w-[290px] cursor-pointer">{{ $movie->name }}</h1>
</div>
