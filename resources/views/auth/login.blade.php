@extends('adminlte.layouts.auth')

@section('title', 'Login')
@section('content')

<body class="hold-transition login-page" style="background-color: #3490dc;">
    <div class="login-container" style="display: flex; height: 100vh;">
        <div class="login-image" style="flex: 1; background-color: #3490dc; display: flex; align-items: center; justify-content: center;">
            <img src="{{ asset('assetsLanding/img/undraw_hello_re_3evm.svg') }}" alt="Image" class="img-fluid" style="max-width: 100%; height: 400px;">
        </div>
        <div class="login-form-container" style="flex: 1; display: flex; align-items: center; justify-content: center;">
            <div class="login-box" style="width: 100%; max-width: 400px;">
                <div class="login-logo">
                    <a href="{{ route('home') }}" style="color: #3490dc; font-weight: 700; font-size: 2rem;">{{ config('app.name', 'Laravel') }}</a>
                </div>
                <div class="card" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    <div class="card-body login-card-body" style="border-radius: 10px;">
                        @if (Session::has('reset_success'))
                            <div class="alert alert-success" role="alert">
                                {{ Session::get('reset_success') }}
                            </div>
                        @endif

                        <p class="login-box-msg" style="font-size: 1.2rem; color: #555;">Sign in to start your session</p>

                        <form action="{{ route('login') }}" method="post">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" style="border-radius: 30px; padding: 10px;">
                                <div class="input-group-append">
                                    <div class="input-group-text" style="border-radius: 0 30px 30px 0;">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Password" style="border-radius: 30px; padding: 10px;">
                                <div class="input-group-append">
                                    <div class="input-group-text" style="border-radius: 0 30px 30px 0;">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                    <div class="input-group-text" style="border-radius: 0 30px 30px 0;">
                                        <i class="fas fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                                    </div>
                                </div>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <script src="{{ asset('js/togglepassword.js') }}"></script>

                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center icheck-primary">
                                        <div>
                                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label for="remember" style="color: #555;">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                        @if (Route::has('password.request'))
                                        <p class="mb-0 ml-auto">
                                            <a href="{{ route('password.request') }}" style="color: #3490dc;">{{ __('Forgot Your Password?') }}</a>
                                        </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-block" style="border-radius: 30px; background-color: #3490dc; border: none;">{{ __('Login') }}</button>
                                </div>
                            </div>
                        </form>

                        @if (Route::has('register'))
                        <p class="mb-4 text-center">
                            <a href="{{ route('register') }}" style="color: #3490dc;">{{ __('Do Not have an account? Register Here') }}</a>
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection
