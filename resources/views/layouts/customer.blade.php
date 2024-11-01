<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Mirabo Cine</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.14.4/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="alternate icon" class="js-site-favicon" type="*/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/customer/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer/order.css') }}">
    @yield('styles')
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/app.css'])
</head>

<body>
    <div class="relative bg-[#ffffff] w-screen h-[100px] flex items-center justify-center text-black">
        @if (!isset($customer))
            <a href="{{ route('customer.login') }}  " class="font-semibold text-lg hover:text-orange-700 absolute right-32">Log
                in</a>
            @if (Route::has('customer.register'))
                <a href="{{ route('customer.register') }}"
                    class="font-semibold text-lg hover:text-orange-700 absolute right-10">Register</a>
            @endif
        @else
            <li class="nav-item dropdown customer-info absolute right-6">
                <img class="customer-avatar" src="{{ asset($customer['image']) }}" alt="#">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ $customer['name'] }}
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('customer.profile.show', $customer['id']) }}">
                        Profile
                    </a>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        @endif
        @include('components.logo')
        @include('components.navbar')
    </div>
    <main>
        @yield('content')
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.14.4/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        @if (session('error'))
            Swal.fire({
                title: "Error!",
                text: '{{ session('error') }}',
                icon: "error",
            });
        @endif
        @if (session('success'))
            Swal.fire({
                title: "Success!",
                text: '{{ session('success') }}',
                icon: "success",
            });
        @endif
        @if (session('warning'))
            Swal.fire({
                title: "Warning!",
                text: '{{ session('warning') }}',
                icon: "warning",
            })
        @endif
    </script>
    @yield('scripts')
</body>

</html>
