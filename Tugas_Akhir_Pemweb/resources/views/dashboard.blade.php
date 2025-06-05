@extends('layouts.app')

@section('title', 'Dashboard - Project Management System')

@section('content')
<div class="container mx-auto p-4 pt-16"> {{-- pt-16 untuk spasi dari navbar fixed-top --}}
    <h1 class="text-4xl font-extrabold text-gray-800 mb-6 text-center">Selamat Datang, {{ auth()->user()->name }}!</h1>
    <p class="text-lg text-gray-600 text-center mb-8">Ini adalah ringkasan proyek dan tugas Anda.</p>

    {{-- Bagian Statistik Dashboard (dari Blade) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-blue-600 text-white p-6 rounded-lg shadow-lg transform transition-transform duration-200 hover:scale-105">
            <h5 class="text-xl font-semibold mb-2">Total Proyek</h5>
            <p class="text-5xl font-bold">{{ $totalProjects ?? 'N/A' }}</p>
        </div>
        <div class="bg-green-600 text-white p-6 rounded-lg shadow-lg transform transition-transform duration-200 hover:scale-105">
            <h5 class="text-xl font-semibold mb-2">Proyek Saya</h5>
            <p class="text-5xl font-bold">{{ $myProjects ?? 'N/A' }}</p>
        </div>
        <div class="bg-purple-600 text-white p-6 rounded-lg shadow-lg transform transition-transform duration-200 hover:scale-105">
            <h5 class="text-xl font-semibold mb-2">Total Tugas</h5>
            <p class="text-5xl font-bold">{{ $totalTasks ?? 'N/A' }}</p>
        </div>
        <div class="bg-yellow-600 text-white p-6 rounded-lg shadow-lg transform transition-transform duration-200 hover:scale-105">
            <h5 class="text-xl font-semibold mb-2">Tugas Ditugaskan ke Saya</h5>
            <p class="text-5xl font-bold">{{ $myAssignedTasks ?? 'N/A' }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-gray-200 text-gray-800 p-6 rounded-lg shadow-md">
            <h5 class="text-xl font-semibold mb-2">Tugas To Do</h5>
            <p class="text-4xl font-bold">{{ $tasksToDo ?? 'N/A' }}</p>
        </div>
        <div class="bg-gray-200 text-gray-800 p-6 rounded-lg shadow-md">
            <h5 class="text-xl font-semibold mb-2">Tugas In Progress</h5>
            <p class="text-4xl font-bold">{{ $tasksInProgress ?? 'N/A' }}</p>
        </div>
        <div class="bg-gray-200 text-gray-800 p-6 rounded-lg shadow-md">
            <h5 class="text-xl font-semibold mb-2">Tugas Done</h5>
            <p class="text-4xl font-bold">{{ $tasksDone ?? 'N/A' }}</p>
        </div>
    </div>

    {{-- Ini adalah DIV tempat aplikasi React (CRUD Proyek) akan dimuat --}}
    {{-- Konten di dalam div#app ini akan digantikan oleh aplikasi React Anda --}}
    <div id="app" class="mt-10 p-6 bg-white rounded-lg shadow-xl">
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Memuat Aplikasi Proyek...</h2>
        <p class="text-gray-500">Jika ini tidak berubah, pastikan server Vite Anda berjalan (npm run dev).</p>
    </div>

    {{-- Tombol Logout --}}
    <div class="text-center mt-10 mb-8">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-colors duration-200 transform hover:scale-105">
                Logout
            </button>
        </form>
    </div>
</div>
@endsection
