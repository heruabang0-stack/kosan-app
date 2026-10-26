@extends('layouts.app')

@section('title', 'Kelola Pesanan Pelanggan (Admin)')

@section('content')
<div class="row">
    <div class="col-12">

        <div class="d-flex justify-content-between align-items-center mb-4 text-white">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm mb-2">
                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                </a>
                <h2 class="fw-bold mb-0"><i class="bi bi-receipt me-2"></i> Manajemen Pesanan Pelanggan</h2>
            </div>
            <span class="badge bg-light text-primary px-3 py-2 fs-6">
                Total Pesanan: {{ $pesanan->count() }}
            </span>
        </div>

        {{-- FILTER STATUS TAB --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-2 d-flex flex-wrap gap-2">
                <a href="{{ route('admin.pesanan.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}">
                    Semua Pesanan
                </a>
                <a href="{{ route('admin.pesanan.index', ['status' => 'menunggu']) }}" class="btn btn-sm {{ request('status') === 'menunggu' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
                    <i class="bi bi-clock-history me-1"></i> Menunggu Konfirmasi
                </a>
                <a href="{{ route('admin.pesanan.index', ['status' => 'disetujui']) }}" class="btn btn-sm {{ request('status') === 'disetujui' ? 'btn-success' : 'btn-outline-success' }}">
                    <i class="bi bi-check-circle me-1"></i> Disetujui
                </a>
                <a href="{{ route('admin.pesanan.index', ['status' => 'ditolak']) }}" class="btn btn-sm {{ request('status') === 'ditolak' ? 'btn-danger' : 'btn-outline-danger' }}">
                    <i class="bi bi-x-circle me-1"></i> Ditolak
                </a>
            </div>
        </div>

        {{-- TABEL PESANAN --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Pemesan</th>
                                <th>WhatsApp</th>
                                <th>Kos Dipesan</th>
                                <th>Mulai Sewa</th>
                                <th>Durasi</th>
                                <th>Total Biaya</th>
                                <th>Catatan</th>
                                <th>Status</th>
                                <th class="text-center">Aksi Konfirmasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanan as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $item->user->name ?? 'User Dihapus' }}</strong>
                                        <div class="small text-muted">{{ $item->user->email ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->telepon) }}?text=Halo%20{{ urlencode($item->user->name ?? '') }},%20kami%20dari%20CariKos%20terkait%20pesanan%20kos%20{{ urlencode($item->kos->nama_kos ?? '') }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-whatsapp me-1"></i> {{ $item->telepon }}
                                        </a>
                                    </td>
                                    <td>
                                        <strong>{{ $item->kos->nama_kos ?? 'Kos Terhapus' }}</strong>
                                        <div class="small text-muted">{{ $item->kos->tipe ?? '' }}</div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</td>
                                    <td><span class="badge bg-secondary">{{ $item->durasi_bulan }} Bulan</span></td>
                                    <td class="fw-bold text-primary">
                                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="small text-muted" style="max-width: 150px;">
                                        {{ $item->catatan ?? '-' }}
                                    </td>
                                    <td>
                                        @if($item->status === 'menunggu')
                                            <span class="badge bg-warning text-dark py-2 px-2">
                                                <i class="bi bi-clock-history"></i> Menunggu
                                            </span>
                                        @elseif($item->status === 'disetujui')
                                            <span class="badge bg-success py-2 px-2">
                                                <i class="bi bi-check-circle"></i> Disetujui
                                            </span>
                                        @else
                                            <span class="badge bg-danger py-2 px-2">
                                                <i class="bi bi-x-circle"></i> Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            @if($item->status !== 'disetujui')
                                                <form action="{{ route('admin.pesanan.updateStatus', $item->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="disetujui">
                                                    <button type="submit" class="btn btn-sm btn-success" title="Setujui Pesanan">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if($item->status !== 'ditolak')
                                                <form action="{{ route('admin.pesanan.updateStatus', $item->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="ditolak">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Tolak Pesanan">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('admin.pesanan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus riwayat pesanan ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light text-danger" title="Hapus Riwayat">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Tidak ada pesanan dengan status ini.
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
