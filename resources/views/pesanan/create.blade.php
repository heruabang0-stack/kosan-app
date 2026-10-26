@extends('layouts.app')

@section('title', 'Pesan Kos - ' . $kos->nama_kos)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <a href="{{ route('kos.show', $kos->id) }}" class="btn btn-outline-light btn-sm mb-2">
                    <i class="bi bi-arrow-left"></i> Kembali ke Detail Kos
                </a>
                <h2 class="fw-bold text-white mb-0">Form Pemesanan Kos</h2>
            </div>
            <span class="badge bg-light text-primary px-3 py-2 fs-6">
                Langkah 1 dari 1: Isi Data Sewa
            </span>
        </div>

        <div class="row g-4">
            {{-- Ringkasan Kos --}}
            <div class="col-md-5">
                <div class="card h-100 shadow-sm border-0">
                    @if($kos->foto)
                        <img src="{{ asset('foto/' . $kos->foto) }}" class="card-img-top" style="height: 220px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 220px;">
                            <i class="bi bi-house-door" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="fw-bold mb-0">{{ $kos->nama_kos }}</h5>
                            <span class="badge bg-info">{{ $kos->tipe }}</span>
                        </div>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $kos->alamat }}
                        </p>

                        <div class="p-3 bg-light rounded-3 mb-3">
                            <div class="text-muted small">Harga Sewa:</div>
                            <div class="fs-4 fw-bold text-primary">
                                Rp {{ number_format($kos->harga, 0, ',', '.') }}
                                <small class="text-muted fs-6 fw-normal">/ bulan</small>
                            </div>
                        </div>

                        <div class="small">
                            <strong><i class="bi bi-stars text-warning me-1"></i> Fasilitas:</strong>
                            <p class="text-muted mb-0 mt-1">{{ $kos->fasilitas }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Pemesanan --}}
            <div class="col-md-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-clipboard2-check me-2"></i> Detail Penyewaan</h5>
                    </div>
                    <div class="card-body p-4">

                        @if ($errors->any())
                            <div class="alert alert-danger py-2 small">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('pesanan.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="kos_id" value="{{ $kos->id }}">

                            {{-- Nama Pemesan (Readonly) --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Nama Pemesan</label>
                                <input type="text" class="form-control bg-light" value="{{ $user->name }}" readonly>
                            </div>

                            {{-- Nomor Telepon / WA --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Nomor WhatsApp / Telepon <span class="text-danger">*</span></label>
                                <input 
                                    type="tel" 
                                    name="telepon" 
                                    class="form-control @error('telepon') is-invalid @enderror" 
                                    value="{{ old('telepon', $user->telepon) }}" 
                                    placeholder="Contoh: 08123456789" 
                                    required>
                                <small class="text-muted">Nomor ini akan dihubungi oleh admin untuk konfirmasi pembayaran dan kunci.</small>
                            </div>

                            <div class="row">
                                {{-- Tanggal Mulai --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Mulai Tanggal <span class="text-danger">*</span></label>
                                    <input 
                                        type="date" 
                                        name="tanggal_mulai" 
                                        id="tanggalMulai" 
                                        class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                        min="{{ date('Y-m-d') }}" 
                                        value="{{ old('tanggal_mulai', date('Y-m-d')) }}" 
                                        required>
                                </div>

                                {{-- Durasi Sewa --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Durasi Sewa <span class="text-danger">*</span></label>
                                    <select name="durasi_bulan" id="durasiBulan" class="form-select @error('durasi_bulan') is-invalid @enderror" required>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('durasi_bulan') == $i ? 'selected' : '' }}>
                                                {{ $i }} Bulan
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            {{-- Catatan Tambahan --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Catatan Tambahan (Opsional)</label>
                                <textarea 
                                    name="catatan" 
                                    class="form-control" 
                                    rows="2" 
                                    placeholder="Contoh: Saya berencana membawa motor, apakah parkiran tersedia?">{{ old('catatan') }}</textarea>
                            </div>

                            {{-- Rincian Biaya Live Calculation --}}
                            <div class="card bg-light border-0 p-3 mb-4">
                                <div class="d-flex justify-content-between mb-1 small text-muted">
                                    <span>Harga per bulan:</span>
                                    <span>Rp {{ number_format($kos->harga, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 small text-muted">
                                    <span>Durasi sewa:</span>
                                    <span id="labelDurasi">1 Bulan</span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong class="fs-6">Total Biaya:</strong>
                                    <strong class="fs-4 text-primary" id="labelTotal">
                                        Rp {{ number_format($kos->harga, 0, ',', '.') }}
                                    </strong>
                                </div>
                            </div>

                            {{-- Tombol Submit --}}
                            <button type="submit" class="btn btn-primary w-100 py-2 fs-6 fw-bold">
                                <i class="bi bi-send-check me-2"></i> Ajukan Pemesanan Sekarang
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    const hargaPerBulan = {{ $kos->harga }};
    const selectDurasi = document.getElementById('durasiBulan');
    const labelDurasi = document.getElementById('labelDurasi');
    const labelTotal = document.getElementById('labelTotal');

    function hitungTotal() {
        const durasi = parseInt(selectDurasi.value) || 1;
        const total = hargaPerBulan * durasi;
        labelDurasi.innerText = durasi + ' Bulan';
        labelTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    selectDurasi.addEventListener('change', hitungTotal);
    hitungTotal();
</script>
@endsection
