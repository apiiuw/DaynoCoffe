@extends('adminlte.layouts.auth')

@section('title', 'Login')
@section('content')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<body style="margin: 0; height: 100vh; background-color: #523A28; display: flex; justify-content: center; align-items: center;">
    <div class="login-box" data-aos="zoom-in" data-aos-duration="800" data-aos-easing="ease-in-out"  style="background: white; padding: 40px 30px; border-radius: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.2); width: 100%; max-width: 400px;">
        @if (Session::has('reset_success'))
            <div class="alert alert-success" role="alert">
                {{ Session::get('reset_success') }}
            </div>
        @endif

        <div class="text-center mb-4">
            <img src="{{ asset('assetsLanding/img/1.png') }}" alt="Welcome" style="max-width: 200px;">
            <h4 class="mt-3" style="color: #7d2a09;">Dayno Kopi</h4>
        </div>

        <form action="{{ route('login') }}" method="post">
            @csrf
            <div class="form-group">
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                    value="{{ old('email') }}" style="border-radius: 30px; padding: 10px 20px;">
                @error('email')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group position-relative">
                <input type="password" name="password" id="password"
                    class="form-control @error('password') is-invalid @enderror" placeholder="Password"
                    style="border-radius: 30px; padding: 10px 20px; padding-right: 40px;">
                <span class="position-absolute" style="top: 50%; right: 20px; transform: translateY(-50%); cursor: pointer;">
                    <i class="fas fa-eye" id="togglePassword"></i>
                </span>
                @error('password')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

           

            <button type="submit" class="btn btn-primary btn-block"
                style="border-radius: 30px; background-color: #523A28; border: none;">Login</button>
        </form>
    </div>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init();
</script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
@endsection
