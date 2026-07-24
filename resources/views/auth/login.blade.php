<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success border-0 bg-success bg-opacity-20 text-success small mb-4">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('status') }}
        </div>
    @endif

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger border-0 bg-danger bg-opacity-25 text-white small mb-4">
            <div class="fw-semibold mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Terdapat kesalahan:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="fa-solid fa-envelope me-1 text-info"></i> Alamat Email
            </label>
            <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="masukkan email anda...">
        </div>

        <!-- Password -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label mb-0">
                    <i class="fa-solid fa-lock me-1 text-info"></i> Kata Sandi
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-footer-link small">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password" placeholder="••••••••">
        </div>

        <!-- Remember Me -->
        <div class="form-check mb-4">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label text-muted-custom small">
                Ingat saya di perangkat ini
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary-custom mb-3">
            <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk Sekarang
        </button>
    </form>

    <!-- Quick Demo Accounts Helper -->
    <div class="p-3 mt-4 rounded-3" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.08);">
        <div class="small fw-semibold text-muted-custom mb-2">
            <i class="fa-solid fa-key text-warning me-1"></i> Demo Quick Fill:
        </div>
        <div class="d-flex gap-2">
            <button type="button" onclick="fillCredentials('admin@example.com', 'password')" class="btn btn-outline-danger btn-sm flex-fill">
                <i class="fa-solid fa-crown me-1"></i> Admin Demo
            </button>
            <button type="button" onclick="fillCredentials('user@example.com', 'password')" class="btn btn-outline-info btn-sm flex-fill">
                <i class="fa-solid fa-user me-1"></i> User Demo
            </button>
        </div>
    </div>

    <!-- Register Link -->
    <div class="text-center mt-4 pt-2 border-top border-secondary border-opacity-25">
        <p class="text-muted-custom small mb-0">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="auth-footer-link fw-semibold ms-1">
                Daftar Akun Baru <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </p>
    </div>

    <script>
        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</x-guest-layout>
