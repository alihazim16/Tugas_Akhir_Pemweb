<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Project Management System'))</title>

    {{-- Vite directives untuk mengkompilasi aset React dan Tailwind CSS --}}
    @viteReactRefresh
    @vite('resources/js/app.tsx')

    {{-- Gaya kustom tambahan jika diperlukan, atau bisa langsung di app.css --}}
    <style>
        .login-bg-gradient {
            background: linear-gradient(to right top, #007bff, #6610f2);
        }
        .text-gradient {
            background: linear-gradient(to right, #667eea, #764ba2);
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
                    <a class="text-gray-600 hover:text-blue-600 transition-colors duration-200" href="{{ route('register') }}">Register</a>
                @else
                    <span class="text-gray-600">Halo, {{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
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

    {{-- Footer --}}
    <footer class="text-center mt-5 mb-4 text-gray-500 text-sm">
        &copy; {{ date('Y') }} - Dibangun dengan Laravel & React
    </footer>

    {{-- SCRIPT UNTUK MENYIMPAN JWT TOKEN KE LOCALSTORAGE DAN DIAGNOSA --}}
    <script>
        // Periksa apakah ada token JWT di sesi flash data
        @if(session('jwt_token'))
            const jwtToken = '{{ session('jwt_token') }}';
            localStorage.setItem('jwt_token', jwtToken);
            console.log('--- DIAGNOSA JWT ---');
            console.log('JWT Token ditemukan di sesi dan disimpan di localStorage.');
            console.log('Token:', jwtToken.substring(0, 30) + '...'); // Tampilkan sebagian token
            console.log('--- AKHIR DIAGNOSA JWT ---');
        @else
            console.log('--- DIAGNOSA JWT ---');
            console.log('Tidak ada JWT Token di sesi flash data.');
            console.log('Token di localStorage saat ini:', localStorage.getItem('jwt_token') ? 'Ada' : 'Tidak Ada');
            console.log('--- AKHIR DIAGNOSA JWT ---');
        @endif
    </script>
</body>
</html>
