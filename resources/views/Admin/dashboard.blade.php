@extends('layouts.app')

@section('title', 'Dashboard Panel Admin')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center text-white gap-2">
            <div>
                <h2 class="fw-bold mb-1"><i class="bi bi-speedometer2 me-2"></i> Dashboard Panel Admin</h2>
                <p class="opacity-75 mb-0">Selamat datang, <strong>{{ Auth::user()->name }}</strong>. Anda sedang berada di mode Administrator.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('kos.index') }}" class="btn btn-outline-light btn-sm fw-bold">
                    <i class="bi bi-eye me-1"></i> Lihat Tampilan Pengguna
                </a>
                <a href="{{ route('admin.kos.create') }}" class="btn btn-light btn-sm fw-bold text-primary shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Kos Baru
                </a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline" onsubmit="return confirm('Keluar dari sesi Admin?')">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm fw-bold">
                        <i class="bi bi-box-arrow-right me-1"></i> Keluar (Logout)
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- KARTU STATISTIK --}}
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm bg-white">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-bold">TOTAL KOS</div>
                    <div class="fs-2 fw-bold text-primary">{{ $totalKos }}</div>
                    <a href="{{ route('admin.kos.index') }}" class="small text-decoration-none">Kelola Kos &rarr;</a>
                </div>
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle">
                    <i class="bi bi-building fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm bg-white">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-bold">TOTAL PESANAN</div>
                    <div class="fs-2 fw-bold text-success">{{ $totalPesanan }}</div>
                    <a href="{{ route('admin.pesanan.index') }}" class="small text-decoration-none">Semua Pesanan &rarr;</a>
                </div>
                <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle">
                    <i class="bi bi-receipt fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm bg-white">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-bold">PERLU KONFIRMASI</div>
                    <div class="fs-2 fw-bold text-warning">{{ $pesananMenunggu }}</div>
                    <a href="{{ route('admin.pesanan.index', ['status' => 'menunggu']) }}" class="small text-decoration-none text-warning fw-bold">Konfirmasi Sekarang &rarr;</a>
                </div>
                <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle">
                    <i class="bi bi-clock-history fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm bg-white">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-bold">PENGGUNA AKTIF</div>
                    <div class="fs-2 fw-bold text-info">{{ $totalUser }}</div>
                    <span class="small text-muted">Pelanggan Terdaftar</span>
                </div>
                <div class="bg-info bg-opacity-10 text-info p-3 rounded-circle">
                    <i class="bi bi-people fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL PESANAN TERBARU --}}
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-bell-fill text-warning me-2"></i> 5 Pesanan Kos Masuk Terbaru</h5>
                <a href="{{ route('admin.pesanan.index') }}" class="btn btn-outline-primary btn-sm">
                    Lihat Semua Pesanan
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Pemesan</th>
                                <th>WhatsApp</th>
                                <th>Kos</th>
                                <th>Durasi</th>
                                <th>Total Biaya</th>
                                <th>Status</th>
                                <th>Aksi Konfirmasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesananTerbaru as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->user->name ?? 'User Terhapus' }}</strong>
                                        <div class="small text-muted">{{ $item->user->email ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->telepon) }}" target="_blank" class="text-success text-decoration-none">
                                            <i class="bi bi-whatsapp me-1"></i> {{ $item->telepon }}
                                        </a>
                                    </td>
                                    <td>
                                        <strong>{{ $item->kos->nama_kos ?? 'Kos Terhapus' }}</strong>
                                        <div class="small text-muted">Mulai: {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}</div>
                                    </td>
                                    <td>{{ $item->durasi_bulan }} Bulan</td>
                                    <td class="fw-bold text-primary">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                    <td>
                                        @if($item->status === 'menunggu')
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @elseif($item->status === 'disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status === 'menunggu')
                                            <div class="btn-group btn-group-sm">
                                                <form action="{{ route('admin.pesanan.updateStatus', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="disetujui">
                                                    <button type="submit" class="btn btn-success btn-sm me-1" title="Setujui Pesanan">
                                                        <i class="bi bi-check-lg"></i> Setujui
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.pesanan.updateStatus', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="ditolak">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Tolak Pesanan">
                                                        <i class="bi bi-x-lg"></i> Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-muted small"><i class="bi bi-check2-all"></i> Sudah diproses</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-3 d-block mb-1"></i> Belum ada pesanan yang masuk.
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
