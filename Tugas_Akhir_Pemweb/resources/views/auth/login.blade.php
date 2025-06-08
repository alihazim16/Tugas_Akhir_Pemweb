{{-- resources/views/auth/login.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
        @if (session('status'))
            <div class="mb-4 text-green-600">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" name="email" required autofocus
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" name="password" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
            </div>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
                </div>
                {{-- <div class="text-sm">
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Forgot your password?</a>
                </div> --}}
            </div>
            <div>
                <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Sign In
                </button>
            </div>
        </form>
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Don't have an account?
                {{-- 
                <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    Register here
                </a>
                --}}
            </p>
        </div>
    </div>
</div>
@endsection