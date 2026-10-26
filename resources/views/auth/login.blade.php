@extends('layouts.app')

@section('title', 'Masuk ke Akun')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-lg">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                        <i class="bi bi-box-arrow-in-right fs-2"></i>
                    </div>
                    <h3 class="fw-bold mb-1">Masuk</h3>
                    <p class="text-muted small">Silakan masuk untuk memesan kos atau mengelola web</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger py-2 small">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                            <input 
                                type="email" 
                                name="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                value="{{ old('email') }}" 
                                placeholder="nama@email.com" 
                                required 
                                autofocus>
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kata Sandi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                            <input 
                                type="password" 
                                name="password" 
                                class="form-control" 
                                placeholder="••••••••" 
                                required>
                        </div>
                    </div>

                    {{-- Remember Me --}}
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="remember">Ingat Saya</label>
                    </div>

                    {{-- Tombol Submit --}}
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
                    </button>
                </form>

                <hr class="my-4">

                <div class="text-center small">
                    Belum punya akun? <a href="{{ route('register') }}" class="fw-bold text-decoration-none">Daftar Pengguna Baru</a>
                </div>

                {{-- Kotak Info Akun Demo --}}
                <div class="card bg-light border-0 mt-3">
                    <div class="card-body p-3 small">
                        <div class="fw-bold text-primary mb-1"><i class="bi bi-info-circle me-1"></i> Akun Demo untuk Testing:</div>
                        <div><strong>Admin:</strong> <code>admin@kosan.com</code> / <code>password123</code></div>
                        <div><strong>User:</strong> <code>user@kosan.com</code> / <code>password123</code></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
