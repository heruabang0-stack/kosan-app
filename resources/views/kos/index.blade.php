@extends('layouts.app')

@section('title', 'Daftar Kosan & Peta Indonesia')

@section('content')

{{-- HEADER --}}
<div class="text-center mb-4 text-white">
    <h1 class="fw-bold mb-2">Cari & Pesan Kosan di Indonesia 🏠🇮🇩</h1>
    <p class="lead opacity-75">Jelajahi kosan nyaman di berbagai kota di Indonesia langsung dari peta interaktif</p>
</div>

{{-- PETA INTERAKTIF INDONESIA --}}
<div class="card shadow-lg border-0 mb-4 overflow-hidden">
    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h5 class="mb-0 fw-bold text-dark">
                <i class="bi bi-geo-alt-fill text-danger me-2"></i> Peta Sebaran Kosan Indonesia
            </h5>
            <small class="text-muted">Peta terkunci di wilayah Indonesia. Klik pin kos untuk melihat info dan langsung memesan.</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary px-3 py-2">
                <i class="bi bi-pin-map-fill me-1"></i> {{ $semuaKosMap->count() }} Kosan Tersebar di Indonesia
            </span>
        </div>
    </div>

    {{-- TOMBOL PINTAS KOTA DI INDONESIA --}}
    <div class="bg-light px-3 py-2 border-bottom d-flex flex-wrap gap-1 align-items-center">
        <span class="small fw-bold text-muted me-1"><i class="bi bi-compass me-1"></i> Lompat ke Kota:</span>
        <button type="button" class="btn btn-sm btn-outline-dark py-1 px-2" onclick="zoomIndonesia()">
            🇮🇩 Seluruh Indonesia
        </button>
        <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" onclick="terbangKeKota(-7.78, 110.37, 13)">
            Yogyakarta
        </button>
        <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" onclick="terbangKeKota(-6.22, 106.82, 12)">
            Jakarta
        </button>
        <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" onclick="terbangKeKota(-6.89, 107.61, 13)">
            Bandung
        </button>
        <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" onclick="terbangKeKota(-7.05, 110.43, 13)">
            Semarang
        </button>
        <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" onclick="terbangKeKota(-7.27, 112.75, 13)">
            Surabaya
        </button>
        <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" onclick="terbangKeKota(-7.95, 112.61, 13)">
            Malang
        </button>
        <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" onclick="terbangKeKota(-8.79, 115.17, 13)">
            Bali
        </button>
    </div>

    <div class="card-body p-0">
        <div id="mapKos" style="height: 440px; width: 100%;"></div>
    </div>
</div>

{{-- FORM PENCARIAN & FILTER --}}
<div class="card shadow-sm mb-4">
    <div class="card-body p-4">
        <form action="{{ route('kos.index') }}" method="GET" id="formCari">
            <div class="row g-2">

                {{-- Keyword --}}
                <div class="col-md-4">
                    <label class="form-label small fw-bold mb-1">
                        <i class="bi bi-search"></i> Kata Kunci (Nama / Alamat / Kota)
                    </label>
                    <input 
                        type="text" 
                        name="cari" 
                        class="form-control" 
                        placeholder="Contoh: Yogyakarta, Jakarta, Melati..." 
                        value="{{ request('cari') }}">
                </div>

                {{-- Tipe --}}
                <div class="col-md-2">
                    <label class="form-label small fw-bold mb-1">
                        <i class="bi bi-tag"></i> Tipe Kos
                    </label>
                    <select name="tipe" class="form-select">
                        <option value="">Semua</option>
                        <option value="Putra" {{ request('tipe')=='Putra'?'selected':'' }}>Putra</option>
                        <option value="Putri" {{ request('tipe')=='Putri'?'selected':'' }}>Putri</option>
                        <option value="Campur" {{ request('tipe')=='Campur'?'selected':'' }}>Campur</option>
                    </select>
                </div>

                {{-- Harga Min --}}
                <div class="col-md-2">
                    <label class="form-label small fw-bold mb-1">
                        <i class="bi bi-cash"></i> Harga Min
                    </label>
                    <input 
                        type="number" 
                        name="harga_min" 
                        class="form-control" 
                        placeholder="0"
                        value="{{ request('harga_min') }}">
                </div>

                {{-- Harga Max --}}
                <div class="col-md-2">
                    <label class="form-label small fw-bold mb-1">
                        <i class="bi bi-cash-stack"></i> Harga Max
                    </label>
                    <input 
                        type="number" 
                        name="harga_max" 
                        class="form-control" 
                        placeholder="9999999"
                        value="{{ request('harga_max') }}">
                </div>

                {{-- Urutkan --}}
                <div class="col-md-2">
                    <label class="form-label small fw-bold mb-1">
                        <i class="bi bi-sort-down"></i> Urutkan
                    </label>
                    <select name="urutkan" class="form-select">
                        <option value="terbaru" {{ request('urutkan')=='terbaru'?'selected':'' }}>Terbaru</option>
                        <option value="termurah" {{ request('urutkan')=='termurah'?'selected':'' }}>Termurah</option>
                        <option value="termahal" {{ request('urutkan')=='termahal'?'selected':'' }}>Termahal</option>
                        <option value="nama" {{ request('urutkan')=='nama'?'selected':'' }}>Nama A-Z</option>
                    </select>
                </div>

                {{-- Tombol --}}
                <div class="col-12 d-flex gap-2 mt-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-search"></i> Cari Kos
                    </button>
                    @if(request()->hasAny(['cari', 'tipe', 'harga_min', 'harga_max', 'urutkan']))
                        <a href="{{ route('kos.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Reset Filter
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>
</div>

{{-- DAFTAR KOS --}}
<div class="d-flex justify-content-between align-items-center mb-3 text-white">
    <h5 class="mb-0">
        <i class="bi bi-list-ul"></i> Daftar Kos Tersedia
        <span class="badge bg-light text-primary ms-1">{{ $kos->count() }}</span>
    </h5>
    @auth
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.kos.create') }}" class="btn btn-warning btn-sm fw-bold">
                <i class="bi bi-plus-circle me-1"></i> Tambah Kos (Admin)
            </a>
        @endif
    @endauth
</div>

<div class="row">
    @forelse($kos as $item)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 d-flex flex-column" id="card-kos-{{ $item->id }}">
                @if($item->foto)
                    <img src="{{ asset('foto/' . $item->foto) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                @else
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="bi bi-house-door" style="font-size: 3rem;"></i>
                    </div>
                @endif

                <div class="card-body flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title fw-bold mb-0 text-truncate" title="{{ $item->nama_kos }}">{{ $item->nama_kos }}</h5>
                        @php
                            $warna = $item->tipe == 'Putra' ? 'primary' : ($item->tipe == 'Putri' ? 'danger' : 'success');
                        @endphp
                        <span class="badge bg-{{ $warna }}">{{ $item->tipe }}</span>
                    </div>

                    <p class="card-text small text-muted mb-2">
                        <i class="bi bi-geo-alt-fill text-danger"></i>
                        {{ Str::limit($item->alamat, 60) }}
                    </p>

                    <p class="card-text small mb-3 text-muted">
                        <i class="bi bi-stars text-success"></i> {{ Str::limit($item->fasilitas, 55) }}
                    </p>

                    <h5 class="text-primary fw-bold mb-0">
                        Rp {{ number_format($item->harga, 0, ',', '.') }} 
                        <small class="text-muted fw-normal fs-6">/ bulan</small>
                    </h5>
                </div>

                {{-- FOOTER KARTU: USER MELIHAT, LIHAT PETA, & PESAN --}}
                <div class="card-footer bg-white border-0 pt-0 pb-3 px-3 d-flex gap-2">
                    <a href="{{ route('kos.show', $item->id) }}" class="btn btn-outline-primary btn-sm flex-grow-1">
                        <i class="bi bi-eye"></i> Detail
                    </a>
                    @if($item->latitude && $item->longitude)
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="fokusKePeta({{ $item->latitude }}, {{ $item->longitude }}, {{ $item->id }})" title="Lihat di Peta">
                            <i class="bi bi-pin-map-fill"></i> Peta
                        </button>
                    @endif
                    <a href="{{ route('pesanan.create', $item->id) }}" class="btn btn-primary btn-sm flex-grow-1 fw-bold">
                        <i class="bi bi-bag-plus"></i> Pesan
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-search" style="font-size: 4rem; color: #cbd5e0;"></i>
                    <h4 class="mt-3 fw-bold">Kos Tidak Ditemukan</h4>
                    <p class="text-muted">Tidak ada kos yang cocok dengan kriteria pencarian Anda.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

@endsection

@section('scripts')
<script>
    // ==========================================
    // 1. BATAS WILAYAH NEGARA INDONESIA SAJA
    // ==========================================
    // Koordinat Barat Daya (Rote/Selatan) dan Timur Laut (Sabang/Papua)
    const boundsIndonesia = L.latLngBounds(
        L.latLng(-11.5, 94.5), // Pojok Barat Daya Indonesia
        L.latLng(6.5, 141.5)   // Pojok Timur Laut Indonesia
    );

    // Inisialisasi Peta terkunci di wilayah Indonesia
    const map = L.map('mapKos', {
        center: [-2.5489, 118.0149],  // Titik tengah geografis Indonesia
        zoom: 5,                      // Zoom awal kepulauan Indonesia
        minZoom: 5,                   // Dibatasi: Tidak bisa zoom out sampai ke luar negeri / benua lain
        maxZoom: 18,
        maxBounds: boundsIndonesia,   // Peta terkunci: tidak bisa digeser keluar dari Indonesia
        maxBoundsViscosity: 1.0       // Memantul kembali jika digeser keluar batas Indonesia
    });

    // Tile Layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap kontributor'
    }).addTo(map);

    // ==========================================
    // 2. DATA SEMUA KOS DI INDONESIA
    // ==========================================
    const dataKos = [
        @foreach($semuaKosMap as $k)
            {
                id: {{ $k->id }},
                nama: "{{ addslashes($k->nama_kos) }}",
                alamat: "{{ addslashes($k->alamat) }}",
                tipe: "{{ $k->tipe }}",
                harga: "Rp {{ number_format($k->harga, 0, ',', '.') }}",
                lat: {{ $k->latitude }},
                lng: {{ $k->longitude }},
                detailUrl: "{{ route('kos.show', $k->id) }}",
                pesanUrl: "{{ route('pesanan.create', $k->id) }}",
                foto: "{{ $k->foto ? asset('foto/' . $k->foto) : '' }}"
            },
        @endforeach
    ];

    const markers = {};

    dataKos.forEach(item => {
        const marker = L.marker([item.lat, item.lng]).addTo(map);

        const imgTag = item.foto 
            ? `<img src="${item.foto}" style="width: 100%; height: 95px; object-fit: cover; border-radius: 8px; margin-bottom: 6px;">`
            : '';

        const badgeColor = item.tipe === 'Putra' ? 'primary' : (item.tipe === 'Putri' ? 'danger' : 'success');

        const popupContent = `
            <div style="min-width: 210px; max-width: 250px; font-family: inherit;">
                ${imgTag}
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <strong style="font-size: 14px;">${item.nama}</strong>
                    <span class="badge bg-${badgeColor}" style="font-size: 10px;">${item.tipe}</span>
                </div>
                <div class="text-muted small mb-1" style="font-size: 11px;">
                    <i class="bi bi-geo-alt-fill text-danger"></i> ${item.alamat}
                </div>
                <div class="text-primary fw-bold mb-2" style="font-size: 13px;">
                    ${item.harga} <span class="text-muted fw-normal" style="font-size: 10px;">/ bulan</span>
                </div>
                <div class="d-flex gap-1">
                    <a href="${item.detailUrl}" class="btn btn-outline-primary btn-sm flex-grow-1" style="font-size: 11px; padding: 4px 6px;">Detail</a>
                    <a href="${item.pesanUrl}" class="btn btn-primary btn-sm flex-grow-1 fw-bold" style="font-size: 11px; padding: 4px 6px;">Pesan Kos</a>
                </div>
            </div>
        `;

        marker.bindPopup(popupContent);
        markers[item.id] = marker;
    });

    // Fungsi zoom kembali ke seluruh Indonesia
    function zoomIndonesia() {
        map.flyToBounds(boundsIndonesia, { padding: [20, 20], duration: 1.2 });
    }

    // Fungsi lompat ke kota tertentu di Indonesia
    function terbangKeKota(lat, lng, zoomLevel) {
        map.flyTo([lat, lng], zoomLevel, { animate: true, duration: 1.2 });
    }

    // Fungsi klik tombol "Peta" pada kartu kos
    function fokusKePeta(lat, lng, kosId) {
        map.flyTo([lat, lng], 16, { animate: true, duration: 1.2 });
        document.getElementById('mapKos').scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        if (markers[kosId]) {
            setTimeout(() => markers[kosId].openPopup(), 1200);
        }
    }
</script>
@endsection
