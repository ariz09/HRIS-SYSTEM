@extends('layouts.guest')

@section('content')
<main class="min-h-screen flex items-center justify-center bg-gray-100 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-sm sm:max-w-md bg-white shadow-xl rounded-2xl min-h-[600px] p-6 sm:p-8 space-y-6 flex flex-col justify-between">

        <!-- Logo -->
        <div class="flex justify-center mt-4 mb-2">
            <img class="h-[4.8rem] sm:h-[5.6rem] md:h-[6.4rem] w-auto" src="{{ asset('images/logo2.png') }}" alt="logo">
        </div>

        <!-- Form Section -->
        <div class="flex-1 flex flex-col justify-center">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input id="name" type="text" name="name" required autofocus
                        class="mt-1 block w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" required
                        class="mt-1 block w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required
                        class="mt-1 block w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <ul class="text-xs text-gray-600 mt-2 mb-0 ps-4 list-decimal">
                        <li><b>Minimum Length</b> – Usually at least 8 characters</li>
                        <li><b>Uppercase Letters</b> – At least one uppercase letter (A–Z)</li>
                        <li><b>Lowercase Letters</b> – At least one lowercase letter (a–z)</li>
                        <li><b>Numbers</b> – At least one digit (0–9)</li>
                        <li><b>Special Characters</b> – At least one symbol (e.g., @, #, !, %)</li>
                        <li><b>No Common Words</b> – Avoid words like <code>password</code>, <code>123456</code>, or <code>admin</code></li>
                        <li><b>No Username or Personal Info</b> – Should not include your name, email, or birthdate</li>
                    </ul>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="mt-1 block w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Register
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-blue-600 hover:underline">
                Already registered?
            </a>
        </div>
    </div>
</main>
@endsection
