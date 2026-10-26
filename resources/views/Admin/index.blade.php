@extends('layouts.app')

@section('title', 'Daftar Kosan')

@section('content')

{{-- HEADER --}}
<div class="text-center mb-4">
    <h1 class="fw-bold">Cari Kosan Impianmu 🏠</h1>
    <p class="text-muted">Temukan kos nyaman sesuai budget dan kebutuhanmu</p>
</div>

{{-- FORM PENCARIAN --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('kos.index') }}" method="GET" id="formCari">
            <div class="row g-2">

                {{-- Keyword --}}
                <div class="col-md-4">
                    <label class="form-label small fw-bold mb-1">
                        <i class="bi bi-search"></i> Kata Kunci
                    </label>
                    <input 
                        type="text" 
                        name="cari" 
                        class="form-control" 
                        placeholder="Nama kos atau alamat..." 
                        value="{{ request('cari') }}">
                </div>

                {{-- Tipe --}}
                <div class="col-md-2">
                    <label class="form-label small fw-bold mb-1">
                        <i class="bi bi-tag"></i> Tipe
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
                        <i class="bi bi-search"></i> Cari Sekarang
                    </button>
                    @if(request()->hasAny(['cari', 'tipe', 'harga_min', 'harga_max', 'urutkan']))
                        <a href="{{ route('kos.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>
</div>

{{-- HASIL PENCARIAN --}}
@if(request()->hasAny(['cari', 'tipe', 'harga_min', 'harga_max']))
    <div class="alert alert-info d-flex justify-content-between align-items-center">
        <div>
            <i class="bi bi-funnel-fill"></i>
            Menampilkan <strong>{{ $kos->count() }}</strong> kos
            @if(request('cari'))
                untuk kata kunci "<strong>{{ request('cari') }}</strong>"
            @endif
            @if(request('tipe'))
                dengan tipe <strong>{{ request('tipe') }}</strong>
            @endif
            @if(request('harga_min') || request('harga_max'))
                di rentang harga
                <strong>
                    Rp {{ number_format(request('harga_min') ?? 0, 0, ',', '.') }}
                    -
                    Rp {{ number_format(request('harga_max') ?? 99999999, 0, ',', '.') }}
                </strong>
            @endif
        </div>
        <a href="{{ route('kos.index') }}" class="btn btn-sm btn-outline-secondary">
            Lihat semua
        </a>
    </div>
@else
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">
            <i class="bi bi-list-ul"></i> Semua Kos 
            <span class="badge bg-primary">{{ $kos->count() }}</span>
        </h5>
    </div>
@endif

{{-- DAFTAR KOS --}}
<div class="row">
    @forelse($kos as $item)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                @if($item->foto)
                    <img src="{{ asset('foto/' . $item->foto) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                @else
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="bi bi-image" style="font-size: 3rem;"></i>
                    </div>
                @endif

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title fw-bold mb-0">{{ $item->nama_kos }}</h5>
                        @php
                            $warna = $item->tipe == 'Putra' ? 'primary' : ($item->tipe == 'Putri' ? 'danger' : 'success');
                        @endphp
                        <span class="badge bg-{{ $warna }}">{{ $item->tipe }}</span>
                    </div>

                    <p class="card-text small text-muted mb-2">
                        <i class="bi bi-geo-alt-fill text-danger"></i>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($item->alamat) }}" 
                        target="_blank" 
                        class="text-decoration-none text-muted"
                        title="Buka di Google Maps">
                        {{ $item->alamat }}
                        <i class="bi bi-box-arrow-up-right" style="font-size: 0.75rem;"></i>
                        </a>
                    </p>

                    <p class="card-text small mb-3">
                        <i class="bi bi-list-check text-success"></i> {{ Str::limit($item->fasilitas, 60) }}
                    </p>

                    <h5 class="text-primary fw-bold mb-0">
                        Rp {{ number_format($item->harga, 0, ',', '.') }} 
                        <small class="text-muted fw-normal">/ bulan</small>
                    </h5>
                </div>

                <div class="card-footer bg-white border-0 d-flex justify-content-between">
                    <a href="{{ route('kos.show', $item->id) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-eye"></i> Detail
                    </a>
                    <div>
                        <a href="{{ route('kos.edit', $item->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('kos.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus kos ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-search" style="font-size: 4rem; color: #cbd5e0;"></i>
                    <h4 class="mt-3">Kos Tidak Ditemukan</h4>
                    <p class="text-muted">
                        @if(request()->hasAny(['cari', 'tipe', 'harga_min', 'harga_max']))
                            Coba ubah kata kunci atau filter pencarian Anda
                        @else
                            Belum ada data kos. Silakan tambahkan data baru
                        @endif
                    </p>
                    <div class="mt-3">
                        @if(request()->hasAny(['cari', 'tipe', 'harga_min', 'harga_max']))
                            <a href="{{ route('kos.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Reset Pencarian
                            </a>
                        @endif
                        <a href="{{ route('kos.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Kos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforelse
</div>

{{-- BANTUAN PENCARIAN --}}
@if(!request()->hasAny(['cari', 'tipe', 'harga_min', 'harga_max']))
<div class="card border-0 bg-light mt-3">
    <div class="card-body">
        <h6 class="fw-bold"><i class="bi bi-lightbulb"></i> Tips Pencarian:</h6>
        <ul class="mb-0 small text-muted">
            <li>Ketik <strong>nama kos</strong> atau <strong>alamat</strong> untuk pencarian bebas</li>
            <li>Pilih <strong>Tipe</strong> untuk filter kos khusus Putra/Putri/Campur</li>
            <li>Isi <strong>Harga Min</strong> dan <strong>Harga Max</strong> untuk filter sesuai budget</li>
            <li>Gunakan <strong>Urutkan</strong> untuk menyortir hasil (termurah, termahal, dll)</li>
        </ul>
    </div>
</div>
@endif

@endsection