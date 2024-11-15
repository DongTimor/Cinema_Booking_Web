@extends('layouts.customer')

@section('content')
    <div class="flex flex-col items-center justify-center">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mt-5">
            @foreach ($movies as $movie)
                @include('components.movie-card', ['movie' => $movie])
            @endforeach
            <div class="w-72 h-fit group">
            </div>
            <div id="trailerModal" class="hidden"> 
                <div 
                    class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-10 flex">
                    <div class="bg-white rounded-lg overflow-hidden w-[90%] md:w-[800px]">
                        <div class="relative text-black">
                            <button id="closeModal"
                                class="absolute top-2 right-2 text-[30px] hover:text-white z-20">✖</button>
                            <iframe id="trailerVideo" class="w-full h-[500px]" src="" frameborder="0"
                                allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script>
        const trailerModal = document.getElementById('trailerModal');
        const closeModal = document.getElementById('closeModal');
        const trailerVideo = document.getElementById('trailerVideo');
        document.querySelectorAll('.trailer-button').forEach(button => {
            button.addEventListener('click', () => {
                const videoUrl = button.getAttribute('data-video-url');
                const videoId = new URL(videoUrl).searchParams.get("v");
                const embedUrl = `https://www.youtube.com/embed/${videoId}`;
                trailerVideo.src = embedUrl;
                trailerModal.classList.remove('hidden');
            });
        });

        closeModal.addEventListener('click', () => {
            trailerModal.classList.add('hidden');
            trailerVideo.src = '';
        });

        trailerModal.addEventListener('click', (event) => {
            if (event.target === trailerModal) {
                trailerModal.classList.add('hidden');
                trailerVideo.src = '';
            }
        });
    </script>
@endsection
