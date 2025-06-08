{{-- resources/views/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Project Management System') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Custom styles here */
        .gradient-text {
            background: linear-gradient(90deg, #2563eb, #1e40af);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-gray-100 antialiased font-sans">

    {{-- Navbar --}}
    <nav class="bg-white shadow-sm py-3 fixed w-full top-0 z-10">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a class="text-xl font-semibold text-gray-800 hover:text-blue-600 transition-colors duration-200" href="{{ url('/') }}">
                {{ config('app.name', 'Project Management System') }}
            </a>
            <div class="flex items-center space-x-4">
                @guest
                    <a class="text-gray-600 hover:text-blue-600 transition-colors duration-200" href="{{ route('login') }}">Login</a>
                    {{-- <a class="text-gray-600 hover:text-blue-600 transition-colors duration-200" href="{{ route('register') }}">Register</a> --}}
                @else
                    <span class="text-gray-600">Halo, {{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-red-600 transition-colors duration-200">Logout</button>
                    </form>
                @endguest
            </div>
        </div>
    </nav>

    {{-- Area Konten Utama --}}
    <main class="min-h-screen flex flex-col items-center justify-center p-4 pt-16">
        <div id="app" class="w-full max-w-7xl mx-auto">
            @yield('content')
        </div>
    </main>

</body>
</html>