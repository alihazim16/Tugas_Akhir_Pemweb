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
            /* Contoh gaya gradien untuk latar belakang atau teks, sesuai desain login sebelumnya */
            .login-bg-gradient {
                background: linear-gradient(to right top, #007bff, #6610f2); /* Biru dan ungu */
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
                        {{-- Tautan untuk tamu (belum login) --}}
                        <a class="text-gray-600 hover:text-blue-600 transition-colors duration-200" href="{{ route('login') }}">Login</a>
                        <a class="text-gray-600 hover:text-blue-600 transition-colors duration-200" href="{{ route('register') }}">Register</a>
                    @else
                        {{-- Tautan untuk pengguna yang sudah login --}}
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
            {{-- Ini adalah DIV tempat aplikasi React mungkin dimuat (jika ada) --}}
            {{-- Namun, untuk halaman login, ini akan diisi oleh @yield('content') --}}
            <div class="w-full max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

        {{-- Footer --}}
        <footer class="text-center mt-5 mb-4 text-gray-500 text-sm">
            &copy; {{ date('Y') }} - Dibangun dengan Laravel & React
        </footer>

        {{-- Skrip ini tidak lagi digunakan untuk menyimpan JWT secara langsung, hanya untuk diagnosa --}}
        <script>
            console.log('--- DIAGNOSA JWT (layouts/app.blade.php) ---');
            console.log('Skrip di layouts/app.blade.php ini tidak lagi menyimpan JWT ke localStorage secara langsung.');
            console.log('Penyimpanan JWT sekarang dilakukan di /token-receiver.');
            console.log('Token di localStorage saat ini:', localStorage.getItem('jwt_token') ? 'Ada' : 'Tidak Ada');
            console.log('--- AKHIR DIAGNOSA JWT ---');
        </script>
    </body>
    </html>
    