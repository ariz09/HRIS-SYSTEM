@extends('layouts.guest')
@section('content')
<main>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                   
                     <div class="card-header"><h3 class="text-center font-weight-light my-4">HRIS Login</h3></div>
                    <div class="card-body">
                         <form action="{{ route('login') }}" method="post">
                             @csrf
                            <div class="form-floating mb-3">
                                <input class="form-control" id="email" name="email" type="email" placeholder="name@example.com" required autofocus/>
                                <label for="email">Email</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                <label for="password">Password</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                                <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                 <a href="{{ route('password.request') }}">Forgot password?</a>
                               <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3">
                        <div class="small"> <a href="{{ route('register') }}">Need an account? Sign up!</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection
