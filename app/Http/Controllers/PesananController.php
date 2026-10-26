<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    // Halaman daftar pesanan saya (User)
    public function index()
    {
        $pesanan = Auth::user()->pesanan()
            ->with('kos')
            ->latest()
            ->get();

        return view('pesanan.index', compact('pesanan'));
    }

    // Tampilkan formulir pemesanan kos
    public function create($kos_id)
    {
        $kos = Kos::findOrFail($kos_id);
        $user = Auth::user();

        return view('pesanan.create', compact('kos', 'user'));
    }

    // Simpan data pemesanan
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kos_id'        => 'required|exists:kos,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'durasi_bulan'  => 'required|integer|min:1|max:36',
            'telepon'       => 'required|string|max:20',
            'catatan'       => 'nullable|string|max:500',
        ], [
            'tanggal_mulai.required'       => 'Tanggal mulai sewa wajib diisi.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh tanggal masa lalu.',
            'durasi_bulan.required'        => 'Durasi sewa wajib dipilih.',
            'durasi_bulan.min'             => 'Durasi minimal 1 bulan.',
            'telepon.required'             => 'Nomor telepon/WhatsApp wajib diisi.',
        ]);

        $kos = Kos::findOrFail($validated['kos_id']);
        $totalHarga = $kos->harga * $validated['durasi_bulan'];

        Pesanan::create([
            'user_id'       => Auth::id(),
            'kos_id'        => $kos->id,
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'durasi_bulan'  => $validated['durasi_bulan'],
            'total_harga'   => $totalHarga,
            'status'        => 'menunggu',
            'catatan'       => $validated['catatan'] ?? null,
            'telepon'       => $validated['telepon'],
        ]);

        return redirect()->route('pesanan.index')
            ->with('success', 'Pesanan Anda untuk ' . $kos->nama_kos . ' berhasil dikirim! Menunggu konfirmasi dari admin.');
    }
}
