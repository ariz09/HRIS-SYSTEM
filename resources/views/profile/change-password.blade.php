@extends('layouts.app')

@section('content')
<div class="py-4">
    <div class="max-w-2xl mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Change Password</h5>
            </div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('profile.update-password') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                               id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <ul class="text-muted small mt-2 mb-0 ps-4 list-decimal">
                            <li><b>Minimum Length</b> – Usually at least 8 characters</li>
                            <li><b>Uppercase Letters</b> – At least one uppercase letter (A–Z)</li>
                            <li><b>Lowercase Letters</b> – At least one lowercase letter (a–z)</li>
                            <li><b>Numbers</b> – At least one digit (0–9)</li>
                            <li><b>Special Characters</b> – At least one symbol (e.g., @, #, !, %)</li>
                            <li><b>No Common Words</b> – Avoid words like <code>password</code>, <code>123456</code>, or <code>admin</code></li>
                            <li><b>No Username or Personal Info</b> – Should not include your name, email, or birthdate</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control"
                               id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-danger">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
