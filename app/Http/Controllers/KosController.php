<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;

class KosController extends Controller
{
    // 1. Tampilkan daftar kos + pencarian + data semua kos untuk peta
    public function index(Request $request)
    {
        $query = Kos::query();

        if ($request->filled('cari')) {
            $keyword = $request->cari;
            $query->where(function($q) use ($keyword) {
                $q->where('nama_kos', 'like', '%' . $keyword . '%')
                  ->orWhere('alamat', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('harga_max')) {
            $query->where('harga', '<=', $request->harga_max);
        }

        if ($request->filled('harga_min')) {
            $query->where('harga', '>=', $request->harga_min);
        }

        $urutkan = $request->get('urutkan', 'terbaru');
        switch ($urutkan) {
            case 'termurah':
                $query->orderBy('harga', 'asc');
                break;
            case 'termahal':
                $query->orderBy('harga', 'desc');
                break;
            case 'nama':
                $query->orderBy('nama_kos', 'asc');
                break;
            default:
                $query->latest();
        }

        $kos = $query->get();
        // Seluruh kos dengan koordinat untuk ditampilkan pada peta Indonesia
        $semuaKosMap = Kos::whereNotNull('latitude')->whereNotNull('longitude')->get();

        return view('kos.index', compact('kos', 'semuaKosMap'));
    }

    // 2. Tampilkan form tambah kos (jika diakses via kos.create)
    public function create()
    {
        return view('Admin.kos.create');
    }

    // 3. Simpan data kos baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kos'  => 'required|string|max:255',
            'alamat'    => 'required|string',
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'harga'     => 'required|numeric|min:0',
            'tipe'      => 'required|in:Putra,Putri,Campur',
            'fasilitas' => 'required|string',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('foto'), $namaFile);
            $data['foto'] = $namaFile;
        }

        Kos::create($data);

        return redirect()->route('kos.index')
                         ->with('success', 'Data kos berhasil ditambahkan!');
    }

    // 4. Tampilkan detail satu kos
    public function show($id)
    {
        $kos = Kos::findOrFail($id);
        return view('kos.show', compact('kos'));
    }

    // 5. Tampilkan form edit kos
    public function edit($id)
    {
        $kos = Kos::findOrFail($id);
        return view('Admin.kos.edit', compact('kos'));
    }

    // 6. Update data kos
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kos'  => 'required|string|max:255',
            'alamat'    => 'required|string',
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'harga'     => 'required|numeric|min:0',
            'tipe'      => 'required|in:Putra,Putri,Campur',
            'fasilitas' => 'required|string',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $kos = Kos::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('foto')) {
            if ($kos->foto && file_exists(public_path('foto/' . $kos->foto))) {
                unlink(public_path('foto/' . $kos->foto));
            }
            $file = $request->file('foto');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('foto'), $namaFile);
            $data['foto'] = $namaFile;
        }

        $kos->update($data);

        return redirect()->route('kos.index')
                         ->with('success', 'Data kos berhasil diperbarui!');
    }

    // 7. Hapus data kos
    public function destroy($id)
    {
        $kos = Kos::findOrFail($id);

        if ($kos->foto && file_exists(public_path('foto/' . $kos->foto))) {
            unlink(public_path('foto/' . $kos->foto));
        }

        $kos->delete();

        return redirect()->route('kos.index')
                         ->with('success', 'Data kos berhasil dihapus!');
    }
}
