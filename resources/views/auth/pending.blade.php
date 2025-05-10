<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Pending</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 flex flex-col justify-center items-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Account Pending</h2>
                <p class="text-gray-600 mb-6">Your account is currently pending approval. Please wait for an administrator to activate your account.</p>
                <p class="mt-4">If you have any questions, please contact the system administrator.</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
