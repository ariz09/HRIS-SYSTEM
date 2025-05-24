@extends('layouts.guest')

@section('content')
<main class="min-h-screen flex items-center justify-center bg-gray-100 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-sm sm:max-w-md bg-white shadow-xl rounded-2xl min-h-[600px] p-6 sm:p-8 flex flex-col justify-between space-y-6">

        <!-- Logo -->
        <div class="flex justify-center mt-4 mb-2">
            <img class="h-[4.5rem] sm:h-[5.25rem] md:h-[6rem] w-auto" src="{{ asset('images/logo2.png') }}" alt="logo">
        </div>

        <!-- Message Section -->
        <div class="flex-1 flex flex-col justify-center text-center space-y-6">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-900">Account Not Activated</h2>
            
            <p class="text-gray-600">
                No access yet. Please connect with your immediate supervisor to activate your account.
            </p>

            <!-- Action Button -->
            <div class="mt-8">
                @if(auth()->check())
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Return to Login
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Return to Login
                    </a>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-sm text-gray-600">
            Need help? Contact your HR department
        </div>
    </div>
</main>
@endsection