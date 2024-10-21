<div class="relative w-full overflow-hidden mt-7">
    <div class="flex items-center justify-center transition-transform duration-700 ease-in-out" id="slider">
      {{-- @foreach ($slides as $index => $slide) --}}
        <div class="w-4/5">
          <img src="{{ asset('images/banner3.jpg') }}" alt="" class="w-full h-[450px]">
          {{-- <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 text-white p-4">
            <div class="text-center">
              <h2 class="text-2xl font-bold">Ironman</h2>
              <p class="mt-2">Phim hay</p>
              <a href="" class="mt-4 inline-block bg-yellow-500 text-white py-2 px-4 rounded">ĐẶT VÉ NGAY</a>
            </div>
          </div> --}}
        </div>
        
      {{-- @endforeach --}}
    </div>
    
    <!-- Navigation Buttons -->
    <button class="absolute left-0 top-1/2 transform -translate-y-1/2 p-3 bg-gray-700 text-white" onclick="prevSlide()">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
    </button>
    <button class="absolute right-0 top-1/2 transform -translate-y-1/2 p-3 bg-gray-700 text-white" onclick="nextSlide()">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
      </svg>
    </button>
    
    <!-- Indicator Dots -->
    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
      {{-- @foreach ($slides as $i => $slide) --}}
        {{-- <button class="w-3 h-3 rounded-full bg-white opacity-50" onclick="showSlide({{ $i }})"></button> --}}
      {{-- @endforeach --}}
    </div>
  </div>
  
  