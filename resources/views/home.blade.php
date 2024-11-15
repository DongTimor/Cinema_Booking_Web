@extends('layouts.customer')

@section('content')
    <div class="bg-white flex flex-col items-center justify-center">
        <div class="relative mt-7">
            <div class="swiper-container relative overflow-hidden">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="{{ asset('common/banner3.jpg') }}" alt="">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('common/banner4.jpg') }}" alt="">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('common/banner5.jpg') }}" alt="">
                    </div>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <div class="swiper-container mt-10">
            <h1>Favorite Movies</h1>
            <div class="swiper-wrapper">
                @foreach ($favoriteMovies as $movie)
                    <div class="swiper-slide">
                        <div class="card max-w-sm rounded overflow-hidden shadow-lg bg-white">
                            <img src="{{ $movie->image_url }}" alt="{{ $movie->movie_name }}" class="w-full h-48 object-cover">
                            <div class="px-6 py-4">
                                <h3 class="font-bold text-xl mb-2">{{ $movie->movie_name }}</h3>
                                <p class="text-gray-700 text-base">Số vé bán: {{ $movie->total_tickets }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button onclick="window.location.href='{{ route('favorite') }}'" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">See More</button>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>

        <div class="swiper-container mt-10">
            <h1>Today's Events</h1>
            <div class="swiper-wrapper">
                @foreach ($events as $event)
                    <div class="swiper-slide">
                        <div class="card max-w-sm rounded overflow-hidden shadow-lg bg-white">
                            <div class="px-6 py-4">
                                <h3 class="font-bold text-xl mb-2">{{ $event['title'] }}</h3>
                                <p class="text-gray-700 text-base">{{ $event['description'] }}</p>
                                <p class="text-gray-700 text-sm">Discount: {{ $event['discount_percentage'] }}%</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button onclick="window.location.href='{{ route('events') }}'" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">See More</button>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>

        <div class="swiper-container mt-10">
            <h1>Discounted Movies Today</h1>
            <div class="swiper-wrapper">
                @foreach ($events as $event)
                    @foreach ($event['movies'] as $movie)
                        <div class="swiper-slide">
                            <div class="card max-w-sm rounded overflow-hidden shadow-lg bg-white">
                                <img src="{{ $movie['image_url'] }}" alt="{{ $movie['name'] }}" class="w-full h-48 object-cover">
                                <div class="px-6 py-4">
                                    <h3 class="font-bold text-xl mb-2">{{ $movie['name'] }}</h3>
                                    <p class="text-gray-700 text-base">Discounted Price: {{ $movie['price'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
            <button onclick="window.location.href='{{ route('movies') }}'" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">See More</button>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>

        <div class="swiper-container mt-10">
            <h1>Vouchers</h1>
            <div class="swiper-wrapper">
                @foreach ($vouchers as $voucher)
                    <div class="swiper-slide">
                        <div class="card max-w-sm rounded overflow-hidden shadow-lg bg-white">
                            <div class="px-6 py-4">
                                <h3 class="font-bold text-xl mb-2">{{ $voucher->name }}</h3>
                                <p class="text-gray-700 text-base">Discount: {{ $voucher->value }}%</p>
                                <p class="text-gray-700 text-sm">Expires at: {{ $voucher->expires_at }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button onclick="window.location.href='{{ route('vouchers-now') }}'" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">See More</button>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/home/swipe-banner.js') }}"></script>
    <script>
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 3,
            spaceBetween: 20,
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
        });
    </script>
@endsection
