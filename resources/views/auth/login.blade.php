@extends('layouts.guest')

@section('content')
<main class="min-h-screen flex items-center justify-center bg-gray-100 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-sm sm:max-w-md bg-white shadow-xl rounded-2xl min-h-[600px] p-6 sm:p-8 flex flex-col justify-between space-y-6">

        @if (session('inactive'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('inactive') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Logo -->
        <div class="flex justify-center mt-4 mb-2">
            <img class="h-[4.5rem] sm:h-[5.25rem] md:h-[6rem] w-auto" src="{{ asset('images/logo2.png') }}" alt="logo">
        </div>

        <!-- Form Section -->
        <div class="flex-1 flex flex-col justify-center">
            <form action="{{ route('login') }}" method="post" class="space-y-6">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" required autofocus
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <div class="text-right mt-2">
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Sign In
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center">
            <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-blue-600 hover:underline">
                Need an account? Sign up!
            </a>
        </div>
    </div>
</main>
@endsection
