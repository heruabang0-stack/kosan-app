@extends('layouts.app')

@section('title', $kos->nama_kos)

@section('content')
<div class="card">
    <div class="row g-0">
        <div class="col-md-5">
            @if($kos->foto)
                <img src="{{ asset('foto/' . $kos->foto) }}" class="img-fluid rounded-start" style="height: 100%; object-fit: cover;">
            @else
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 300px;">
                    Tidak ada foto
                </div>
            @endif
        </div>
        <div class="col-md-7">
            <div class="card-body">
                <h3 class="card-title">{{ $kos->nama_kos }}</h3>
                <span class="badge bg-info mb-3">{{ $kos->tipe }}</span>
                <p><i class="bi bi-geo-alt-fill"></i> {{ $kos->alamat }}</p>
                <p><i class="bi bi-cash-coin"></i> <strong class="text-primary">Rp {{ number_format($kos->harga, 0, ',', '.') }}</strong> / bulan</p>
                <p><i class="bi bi-list-check"></i> <strong>Fasilitas:</strong><br>{{ $kos->fasilitas }}</p>

                <a href="{{ route('kos.index') }}" class="btn btn-secondary">Kembali</a>
                <a href="{{ route('kos.edit', $kos->id) }}" class="btn btn-warning">Edit</a>
            </div>
        </div>
    </div>
</div>
@endsection