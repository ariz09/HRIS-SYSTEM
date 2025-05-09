@extends('layouts.guest')

@section('content')
<main class="min-h-screen flex items-center justify-center bg-gray-100 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-sm sm:max-w-md bg-white shadow-xl rounded-2xl min-h-[600px] p-6 sm:p-8 flex flex-col justify-between space-y-6">

        <!-- Admin Header -->
        <div class="flex flex-col items-center mt-4 mb-2 space-y-1">
            <img class="h-[4.5rem] sm:h-[5.25rem] md:h-[6rem] w-auto" src="{{ asset('images/logo2.png') }}" alt="logo">
            <h2 class="pt-3 text-sm sm:text-base md:text-lg font-medium text-red-600">Admin Login</h2>
        </div>

        <!-- Login Form -->
        <div class="flex-1 flex flex-col justify-center">
            <form action="{{ route('admin.login') }}" method="post" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" required autofocus
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600">
                        Sign In
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center">
            <p class="text-xs text-gray-500">Admin access only. Contact IT if you need credentials.</p>
        </div>
    </div>
</main>
@endsection
