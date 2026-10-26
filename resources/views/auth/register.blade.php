@extends('layouts.app')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-lg">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                        <i class="bi bi-person-plus fs-2"></i>
                    </div>
                    <h3 class="fw-bold mb-1">Daftar Akun Pengguna</h3>
                    <p class="text-muted small">Buat akun untuk memesan kosan dengan mudah dan cepat</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger py-2 small">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    {{-- Nama Lengkap --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                            <input 
                                type="text" 
                                name="name" 
                                class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name') }}" 
                                placeholder="Contoh: Budi Santoso" 
                                required 
                                autofocus>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Alamat Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                            <input 
                                type="email" 
                                name="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                value="{{ old('email') }}" 
                                placeholder="nama@email.com" 
                                required>
                        </div>
                    </div>

                    {{-- Nomor Telepon / WhatsApp --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-whatsapp"></i></span>
                            <input 
                                type="tel" 
                                name="telepon" 
                                class="form-control @error('telepon') is-invalid @enderror" 
                                value="{{ old('telepon') }}" 
                                placeholder="08123456789" 
                                required>
                        </div>
                        <small class="text-muted">Digunakan oleh admin untuk konfirmasi pemesanan kos.</small>
                    </div>

                    {{-- Password & Konfirmasi --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Kata Sandi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                <input 
                                    type="password" 
                                    name="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    placeholder="Min. 6 karakter" 
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Ulangi Sandi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                                <input 
                                    type="password" 
                                    name="password_confirmation" 
                                    class="form-control" 
                                    placeholder="Ulangi kata sandi" 
                                    required>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Submit --}}
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mt-2">
                        <i class="bi bi-person-check me-1"></i> Daftar Sekarang
                    </button>
                </form>

                <hr class="my-4">

                <div class="text-center small">
                    Sudah punya akun? <a href="{{ route('login') }}" class="fw-bold text-decoration-none">Masuk di sini</a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
