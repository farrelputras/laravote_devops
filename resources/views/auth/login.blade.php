@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-auto">
      <div class="card login-card">
        <div class="card-header">{{ __('Login') }}</div>
        <div class="card-body">
          <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- E-Mail Address --}}
            <div class="form-group">
              <label for="email">E-Mail Address</label>
              <input id="email"
                     type="email"
                     class="form-control @error('email') is-invalid @enderror"
                     name="email"
                     value="{{ old('email') }}"
                     required autofocus>
              @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
              <label for="password">Password</label>
              <input id="password"
                     type="password"
                     class="form-control @error('password') is-invalid @enderror"
                     name="password"
                     required>
              @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            {{-- Remember & Forgot --}}
            <div class="form-group d-flex justify-content-between align-items-center">
              <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox"
                       name="remember" id="remember"
                       {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                  Remember Me
                </label>
              </div>
              @if(Route::has('password.request'))
                <a class="text-primary" href="{{ route('password.request') }}">
                  Forgot Your Password?
                </a>
              @endif
            </div>

            {{-- Buttons --}}
            <div class="form-group d-flex">
              <button type="submit" class="btn btn-login">
                {{ __('Login') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
