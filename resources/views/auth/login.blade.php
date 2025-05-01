@extends('layouts.guest')
@section('content')
<main>
    <div class="container-fluid d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card shadow border-0 rounded-3 mb-5 w-100" style="max-width: 450px;">

            <!-- Logo -->
            <div class="d-flex justify-content-center align-items-center my-4 py-4">
                <img src="{{ asset('images/logo2.png') }}" alt="logo" class="img-fluid" style="max-height: 96px;">
            </div>

            <!-- Form Section -->
            <div class="card-body">
                <form action="{{ route('login') }}" method="post">
                    @csrf

                    <!-- Email Input -->
                    <div class="form-floating mb-3">
                        <input class="form-control" id="email" name="email" type="email" placeholder="name@example.com" required autofocus />
                        <label for="email">Email</label>
                    </div>

                    <!-- Password Input -->
                    <div class="form-floating mb-3">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required />
                        <label for="password">Password</label>
                    </div>

                    <!-- Remember Password Checkbox -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                        <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
                    </div>

                    <!-- Forgot Password Link and Submit Button -->
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <a href="{{ route('password.request') }}" class="text-muted">Forgot password?</a>
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                </form>
            </div>

            <!-- Footer Section -->
            <div class="card-footer text-center py-3">
                <div class="small"><a href="{{ route('register') }}">Need an account? Sign up!</a></div>
            </div>
        </div>
    </div>
</main>
@endsection
