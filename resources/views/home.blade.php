@extends('layouts.customer')

@section('content')
    <div class="bg-white flex flex-col items-center justify-center">
        <div class="relative">
            <div class="swiper-container relative overflow-hidden">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="{{ asset("common/banner3.jpg") }}" alt="">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset("common/banner4.jpg") }}" alt="">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset("common/banner5.jpg") }}" alt="">
                    </div>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
    @endsection
    @section('scripts')
    <script src="{{asset('js/home/swipe-banner.js')}}"></script>
    @endsection
