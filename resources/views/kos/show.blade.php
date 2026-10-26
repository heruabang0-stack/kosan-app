@extends('layouts.app')

@section('title', $kos->nama_kos)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">

        <div class="mb-3 d-flex justify-content-between align-items-center">
            <a href="{{ route('kos.index') }}" class="btn btn-outline-light btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kos
            </a>
            @auth
                @if(Auth::user()->role === 'admin')
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.kos.edit', $kos->id) }}" class="btn btn-warning btn-sm fw-bold">
                            <i class="bi bi-pencil me-1"></i> Edit Data Kos (Admin)
                        </a>
                    </div>
                @endif
            @endauth
        </div>

        <div class="card shadow-sm border-0 overflow-hidden mb-4">
            <div class="row g-0">
                <div class="col-md-6">
                    @if($kos->foto)
                        <img src="{{ asset('foto/' . $kos->foto) }}" class="img-fluid w-100 h-100" style="object-fit: cover; min-height: 380px;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center w-100 h-100" style="min-height: 380px;">
                            <div class="text-center">
                                <i class="bi bi-house-door" style="font-size: 5rem;"></i>
                                <div class="mt-2 text-white-50">Tidak ada foto tersedia</div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-6 d-flex flex-column">
                    <div class="card-body p-4 d-flex flex-column justify-content-between h-100">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h2 class="card-title fw-bold mb-0 text-dark">{{ $kos->nama_kos }}</h2>
                                @php
                                    $warna = $kos->tipe == 'Putra' ? 'primary' : ($kos->tipe == 'Putri' ? 'danger' : 'success');
                                @endphp
                                <span class="badge bg-{{ $warna }} fs-6 px-3 py-2">{{ $kos->tipe }}</span>
                            </div>

                            <p class="text-muted mb-3">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                {{ $kos->alamat }}
                            </p>

                            <div class="p-3 bg-light rounded-3 mb-4">
                                <div class="text-muted small">Harga Sewa:</div>
                                <div class="fs-2 fw-bold text-primary">
                                    Rp {{ number_format($kos->harga, 0, ',', '.') }}
                                    <small class="text-muted fs-6 fw-normal">/ bulan</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h6 class="fw-bold mb-2"><i class="bi bi-stars text-warning me-1"></i> Fasilitas Lengkap:</h6>
                                <p class="text-secondary bg-light p-3 rounded-3 mb-0">
                                    {{ $kos->fasilitas }}
                                </p>
                            </div>
                        </div>

                        {{-- TOMBOL PESAN SEKARANG --}}
                        <div class="pt-3 border-top d-grid gap-2">
                            <a href="{{ route('pesanan.create', $kos->id) }}" class="btn btn-primary btn-lg fw-bold shadow-sm">
                                <i class="bi bi-bag-check-fill me-2"></i> Pesan Kos Ini Sekarang
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- LOKASI KOS DI PETA INTERAKTIF --}}
        <div class="card shadow-sm border-0 overflow-hidden mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-map-fill text-primary me-2"></i> Lokasi Kos di Peta
                </h5>
                @if($kos->latitude && $kos->longitude)
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $kos->latitude }},{{ $kos->longitude }}" 
                       target="_blank" 
                       class="btn btn-outline-success btn-sm">
                        <i class="bi bi-sign-turn-right-fill me-1"></i> Buka Rute Google Maps
                    </a>
                @endif
            </div>
            <div class="card-body p-0">
                @if($kos->latitude && $kos->longitude)
                    <div id="mapDetailKos" style="height: 350px; width: 100%;"></div>
                @else
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-geo-alt-slash fs-1 d-block mb-2"></i>
                        Titik koordinat belum ditambahkan oleh admin untuk kos ini.
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
@if($kos->latitude && $kos->longitude)
<script>
    const lat = {{ $kos->latitude }};
    const lng = {{ $kos->longitude }};
    const map = L.map('mapDetailKos').setView([lat, lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    const marker = L.marker([lat, lng]).addTo(map);
    marker.bindPopup(`
        <div style="font-family: inherit;">
            <strong>{{ addslashes($kos->nama_kos) }}</strong><br>
            <span class="text-muted small">{{ addslashes($kos->alamat) }}</span><br>
            <a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}" target="_blank" class="btn btn-sm btn-success mt-1 text-white py-0 px-2" style="font-size: 11px;">Rute Google Maps</a>
        </div>
    `).openPopup();
</script>
@endif
@endsection
