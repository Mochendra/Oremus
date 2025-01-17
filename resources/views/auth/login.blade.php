@extends('layouts.master-mini')
@section('content')

<div class="content-wrapper d-flex align-items-center justify-content-center auth theme-one" style="background-image: url({{ url('assets/images/auth/login_1.jpg') }}); background-size: cover;">
  <div class="row w-100">
    <div class="col-lg-4 mx-auto">
      <div class="auto-form-wrapper">
        <!-- Use the Laravel form action and method -->
        <form method="POST" action="{{ route('login') }}">
          @csrf <!-- CSRF token for security -->

          <!-- Email Address -->
          <div class="form-group">
            <label class="label">{{ __('Email') }}</label>
            <div class="input-group">
              <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Email">
              <div class="input-group-append">
                <span class="input-group-text">
                  <i class="mdi mdi-check-circle-outline"></i>
                </span>
              </div>
            </div>
            @if($errors->has('email'))
              <small class="text-danger">{{ $errors->first('email') }}</small>
            @endif
          </div>

          <!-- Password -->
          <div class="form-group">
            <label class="label">{{ __('Password') }}</label>
            <div class="input-group">
              <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password" placeholder="*********">
              <div class="input-group-append">
                <span class="input-group-text">
                  <i class="mdi mdi-check-circle-outline"></i>
                </span>
              </div>
            </div>
            @if($errors->has('password'))
              <small class="text-danger">{{ $errors->first('password') }}</small>
            @endif
          </div>

          {{-- <!-- Remember Me -->
          <div class="form-group d-flex justify-content-between">
            <div class="form-check form-check-flat mt-0">
              <label class="form-check-label">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember"> {{ __('Keep me signed in') }}
              </label>
            </div>
            <a href="{{ route('password.request') }}" class="text-small forgot-password text-black">{{ __('Forgot Password') }}</a>
          </div> --}}

          <!-- Submit Button -->
          <div class="form-group">
            <button type="submit" class="btn btn-primary submit-btn btn-block">{{ __('Login') }}</button>
          </div>

          {{-- <!-- Google Login Button -->
          <div class="form-group">
            <button class="btn btn-block g-login">
              <img class="mr-3" src="{{ url('assets/images/file-icons/icon-google.svg') }}" alt="Google Login">Log in with Google
            </button>
          </div> --}}

          <!-- Registration Link -->
          <div class="text-block text-center my-3">
            <span class="text-small font-weight-semibold">{{ __('Not a member ?') }}</span>
            <a href="{{ route('register') }}" class="text-black text-small">{{ __('Create new account') }}</a>
          </div>
        </form>
      </div>
      <!-- Footer links -->
      <ul class="auth-footer">
        <li><a href="#">Conditions</a></li>
        <li><a href="#">Help</a></li>
        <li><a href="#">Terms</a></li>
      </ul>
      <p class="footer-text text-center">copyright © 2018 Bootstrapdash. All rights reserved.</p>
    </div>
  </div>
</div>

@endsection
