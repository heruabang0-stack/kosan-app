@extends('layouts.app')

@section('title', 'Kelola Data Kos (Admin)')

@section('content')
<div class="row">
    <div class="col-12">

        <div class="d-flex justify-content-between align-items-center mb-4 text-white">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm mb-2">
                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                </a>
                <h2 class="fw-bold mb-0"><i class="bi bi-building me-2"></i> Manajemen Data Kos</h2>
            </div>
            <a href="{{ route('admin.kos.create') }}" class="btn btn-light fw-bold text-primary shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Tambah Kos Baru
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama Kos</th>
                                <th>Tipe</th>
                                <th>Harga / Bulan</th>
                                <th>Fasilitas</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kos as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($item->foto)
                                            <img src="{{ asset('foto/' . $item->foto) }}" class="rounded shadow-sm" width="60" height="60" style="object-fit: cover;">
                                        @else
                                            <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $item->nama_kos }}</strong>
                                        <div class="small text-muted">
                                            <i class="bi bi-geo-alt-fill text-danger"></i> {{ Str::limit($item->alamat, 40) }}
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $warna = $item->tipe == 'Putra' ? 'primary' : ($item->tipe == 'Putri' ? 'danger' : 'success');
                                        @endphp
                                        <span class="badge bg-{{ $warna }}">{{ $item->tipe }}</span>
                                    </td>
                                    <td class="fw-bold text-primary">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="small text-muted" style="max-width: 250px;">
                                        {{ Str::limit($item->fasilitas, 50) }}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('kos.show', $item->id) }}" class="btn btn-outline-info" title="Lihat di Web">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.kos.edit', $item->id) }}" class="btn btn-warning" title="Edit Kos">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.kos.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kos {{ $item->nama_kos }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Hapus Kos">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-building-slash fs-1 d-block mb-2"></i>
                                        Belum ada data kos yang ditambahkan.
                                        <div class="mt-2">
                                            <a href="{{ route('admin.kos.create') }}" class="btn btn-primary btn-sm">
                                                <i class="bi bi-plus-circle me-1"></i> Tambah Kos Sekarang
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
