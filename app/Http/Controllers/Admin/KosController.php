<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kos;
use Illuminate\Http\Request;

class KosController extends Controller
{
    // Tampilkan daftar kos di panel admin
    public function index()
    {
        $kos = Kos::latest()->get();
        return view('Admin.kos.index', compact('kos'));
    }

    // Form tambah kos baru
    public function create()
    {
        return view('Admin.kos.create');
    }

    // Simpan kos baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kos'  => 'required|string|max:255',
            'alamat'    => 'required|string',
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'harga'     => 'required|numeric|min:0',
            'tipe'      => 'required|in:Putra,Putri,Campur',
            'fasilitas' => 'required|string',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('foto'), $namaFile);
            $validated['foto'] = $namaFile;
        }

        Kos::create($validated);

        return redirect()->route('admin.kos.index')
            ->with('success', 'Data kos baru berhasil ditambahkan!');
    }

    // Tampilkan detail kos
    public function show($id)
    {
        $kos = Kos::findOrFail($id);
        return view('Admin.kos.show', compact('kos'));
    }

    // Form edit kos
    public function edit($id)
    {
        $kos = Kos::findOrFail($id);
        return view('Admin.kos.edit', compact('kos'));
    }

    // Simpan perubahan kos
    public function update(Request $request, $id)
    {
        $kos = Kos::findOrFail($id);

        $validated = $request->validate([
            'nama_kos'  => 'required|string|max:255',
            'alamat'    => 'required|string',
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'harga'     => 'required|numeric|min:0',
            'tipe'      => 'required|in:Putra,Putri,Campur',
            'fasilitas' => 'required|string',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($kos->foto && file_exists(public_path('foto/' . $kos->foto))) {
                unlink(public_path('foto/' . $kos->foto));
            }
            $file = $request->file('foto');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('foto'), $namaFile);
            $validated['foto'] = $namaFile;
        }

        $kos->update($validated);

        return redirect()->route('admin.kos.index')
            ->with('success', 'Data kos berhasil diperbarui!');
    }

    // Hapus kos
    public function destroy($id)
    {
        $kos = Kos::findOrFail($id);

        if ($kos->foto && file_exists(public_path('foto/' . $kos->foto))) {
            unlink(public_path('foto/' . $kos->foto));
        }

        $kos->delete();

        return redirect()->route('admin.kos.index')
            ->with('success', 'Data kos berhasil dihapus!');
    }
}
