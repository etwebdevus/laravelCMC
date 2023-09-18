@extends('layouts.auth.app')
@section('title','Login')
@section('content')
    <div class="form-content">
        <h1 class="">Log In to <span class="brand-name">{{ Config('app.title') }}</span></h1>
        <form class="text-left" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form">
                <div id="username-field" class="field-wrapper input">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-mail">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                        placeholder="Email Address">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div id="password-field" class="field-wrapper input mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-lock">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2">
                        </rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required placeholder="Password" autocomplete="current-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="d-sm-flex justify-content-between">
                    <div class="field-wrapper toggle-pass">
                        <p class="d-inline-block">Show Password</p>
                        <label class="switch s-primary">
                            <input type="checkbox" id="toggle-password" class="d-none">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="field-wrapper">
                        <button type="submit" class="btn btn-primary" value="">Log In</button>
                    </div>
                </div>
                <div class="field-wrapper text-center keep-logged-in">
                    <div class="n-chk new-checkbox checkbox-outline-primary">
                        <label class="new-control new-checkbox checkbox-outline-primary">
                            <input class="new-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> 
                            <span class="new-control-indicator"></span>Keep me logged in
                        </label>
                    </div>
                </div>
                <div class="field-wrapper">
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-pass-link">Forgot Password?</a>
                    @endif
                </div>
            </div>
        </form>
    </div>
   
@endsection
