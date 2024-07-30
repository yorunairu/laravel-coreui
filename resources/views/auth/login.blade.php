@extends('layouts.guest')

@section('title')
    Login | PT. Kencana Zavira
@endsection

@section('content')
    <link rel="stylesheet" href="{{ URL::to('/css/login.css') }}">
    <!-- login area start -->
    <div class="container-fluid full-width-container">
        <div class="row no-gutters">
            <div class="col-lg-8 d-none d-lg-flex flex-column justify-content-between left-panel">
                <div class="info-section mt-5">
                    <h1 class="text-border">PD System</h1>
                    <h2 class="text-border">Performance Dashboard</h2>
                    <div class="info mt-4" style="opacity: 75%;">
                        <h3>Info</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                            laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                            voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat
                            non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                    </div>
                </div>
                <footer class="text-center">
                    <p class="text-border" style="color: white; font-weight: bold;">Copyright © PT. Kencana Zavira. All
                        Rights Reserved</p>
                </footer>
            </div>
            <div class="col-lg-4 d-flex flex-column justify-content-center right-panel">
                <div class="login-section mx-auto">
                    <h2 class="text-center text-white mb-4">Login Menu</h2>
                    <form method="POST" action="{{ route('login.submit') }}">
                        @csrf
                        <div class="input-group">
                            <input type="text" id="email" name="email" class="form-control"
                                placeholder="Email address or Username">
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control" placeholder="********">
                            <span class="input-group-text toggle-password" style="cursor: pointer;"><i class="fa fa-eye"></i></span>
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group form-check remember-me justify-content-start">
                            <input type="checkbox" id="remember" name="remember" class="form-check-input">
                            <label for="remember" class="form-check-label text-white">Remember Me</label>
                        </div>
                        <button type="submit" class="btn login-btn btn-block">LOGIN</button>
                    </form>
                    <img src="{{ asset('/images/logo_kz.png') }}" alt="logo kz" class="img-fluid mt-4">
                </div>
            </div>
        </div>
    </div>
    <!-- login area end -->

    <!-- Font Awesome for the eye icon -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <script type="module">
        // JavaScript to toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(item => {
            item.addEventListener('click', function() {
                const passwordField = this.previousElementSibling;
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fa fa-eye"></i>' : '<i class="fa fa-eye-slash"></i>';
            });
        });
    </script>
@endsection
