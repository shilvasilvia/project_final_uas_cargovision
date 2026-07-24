<x-guest-layout>
    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger border-0 bg-danger bg-opacity-20 text-danger small mb-4">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">
                <i class="fa-solid fa-user me-1 text-info"></i> Nama Lengkap
            </label>
            <input id="name" type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="masukkan nama anda...">
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="fa-solid fa-envelope me-1 text-info"></i> Alamat Email
            </label>
            <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autocomplete="username" placeholder="contoh: user@cargo.com">
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="fa-solid fa-lock me-1 text-info"></i> Kata Sandi
            </label>
            <input id="password" type="password" name="password" class="form-control" required autocomplete="new-password" placeholder="minimal 8 karakter...">
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">
                <i class="fa-solid fa-circle-check me-1 text-info"></i> Konfirmasi Kata Sandi
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password" placeholder="ulangi kata sandi...">
        </div>

        <!-- Role Information Badge -->
        <div class="p-3 mb-4 rounded-3" style="background: rgba(56, 189, 248, 0.1); border: 1px solid rgba(56, 189, 248, 0.2);">
            <div class="d-flex align-items-center gap-2 text-info small">
                <i class="fa-solid fa-info-circle fs-6"></i>
                <div>
                    Pendaftaran baru akan mendapatkan akses pengguna <strong>User Biasa (Read-Only & Favorites)</strong>.
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary-custom mb-3">
            <i class="fa-solid fa-user-plus me-2"></i> Daftar Akun Sekarang
        </button>
    </form>

    <!-- Login Link -->
    <div class="text-center mt-4 pt-2 border-top border-secondary border-opacity-25">
        <p class="text-muted-custom small mb-0">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="auth-footer-link fw-semibold ms-1">
                <i class="fa-solid fa-right-to-bracket me-1"></i> Masuk di Sini
            </a>
        </p>
    </div>
</x-guest-layout>
