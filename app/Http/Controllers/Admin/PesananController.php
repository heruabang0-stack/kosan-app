<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    // Tampilkan semua pesanan dari pengguna
    public function index(Request $request)
    {
        $query = Pesanan::with(['user', 'kos'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pesanan = $query->get();

        return view('Admin.pesanan.index', compact('pesanan'));
    }

    // Update status pesanan (Konfirmasi / Tolak)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,disetujui,ditolak',
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status = $request->status;
        $pesanan->save();

        $pesan = $request->status === 'disetujui' 
            ? 'Pesanan berhasil disetujui!' 
            : ($request->status === 'ditolak' ? 'Pesanan telah ditolak.' : 'Status pesanan diubah ke menunggu.');

        return redirect()->back()->with('success', $pesan);
    }

    // Hapus pesanan
    public function destroy($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->delete();

        return redirect()->back()->with('success', 'Data pesanan berhasil dihapus.');
    }
}
