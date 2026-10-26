@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-white mb-1"><i class="bi bi-calendar-check me-2"></i> Riwayat Pesanan Saya</h2>
                <p class="text-white-50 mb-0">Pantau status pemesanan kos yang telah Anda ajukan</p>
            </div>
            <a href="{{ route('kos.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-search me-1"></i> Cari Kos Lain
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                @if($pesanan->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 fw-bold">Belum Ada Pesanan</h4>
                        <p class="text-muted">Anda belum pernah memesan kos. Temukan kos idamanmu sekarang!</p>
                        <a href="{{ route('kos.index') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-search me-1"></i> Jelajahi Daftar Kos
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kos</th>
                                    <th>Mulai Sewa</th>
                                    <th>Durasi</th>
                                    <th>Total Biaya</th>
                                    <th>Status</th>
                                    <th>Tanggal Pemesanan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanan as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $item->kos->nama_kos ?? 'Kos telah dihapus' }}</strong>
                                            <div class="small text-muted">
                                                <i class="bi bi-geo-alt-fill text-danger"></i> {{ Str::limit($item->kos->alamat ?? '-', 35) }}
                                            </div>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d M Y') }}</td>
                                        <td><span class="badge bg-secondary">{{ $item->durasi_bulan }} Bulan</span></td>
                                        <td class="fw-bold text-primary">
                                            Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @if($item->status === 'menunggu')
                                                <span class="badge bg-warning text-dark py-2 px-3">
                                                    <i class="bi bi-clock-history me-1"></i> Menunggu Konfirmasi
                                                </span>
                                            @elseif($item->status === 'disetujui')
                                                <span class="badge bg-success py-2 px-3">
                                                    <i class="bi bi-check-circle me-1"></i> Disetujui
                                                </span>
                                            @else
                                                <span class="badge bg-danger py-2 px-3">
                                                    <i class="bi bi-x-circle me-1"></i> Ditolak
                                                </span>
                                            @endif
                                        </td>
                                        <td class="small text-muted">
                                            {{ $item->created_at->translatedFormat('d M Y, H:i') }}
                                        </td>
                                        <td>
                                            @if($item->kos)
                                                <a href="{{ route('kos.show', $item->kos->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Kos">
                                                    <i class="bi bi-eye"></i> Detail
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-info mt-4 mb-0 small">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        <strong>Catatan:</strong> Jika pesanan berstatus <strong>Disetujui</strong>, pihak admin akan segera menghubungi nomor WhatsApp Anda untuk proses pembayaran dan pengambilan kunci kos.
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection
