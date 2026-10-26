@extends('layouts.app')

@section('title', 'Tambah Kos Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-plus-circle"></i> Tambah Kos Baru
                </h4>
            </div>
            <div class="card-body">

                <!-- Tampilkan error validasi jika ada -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Ada kesalahan:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form Tambah Kos -->
                <form action="{{ route('admin.kos.store') }}" method="POST">
    @csrf
    <!-- Input fields -->
    <button type="submit">Simpan</button>
</form>

                    {{-- Nama Kos --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Nama Kos <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="nama_kos" 
                            class="form-control @error('nama_kos') is-invalid @enderror" 
                            value="{{ old('nama_kos') }}" 
                            placeholder="Contoh: Kos Melati Putri"
                            required>
                        @error('nama_kos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Alamat <span class="text-danger">*</span>
                        </label>
                        <textarea 
                            name="alamat" 
                            class="form-control @error('alamat') is-invalid @enderror" 
                            rows="3"
                            placeholder="Contoh: Jl. Kaliurang KM 5, Sleman, Yogyakarta"
                            required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Harga & Tipe --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                Harga / Bulan <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input 
                                    type="number" 
                                    name="harga" 
                                    class="form-control @error('harga') is-invalid @enderror" 
                                    value="{{ old('harga') }}"
                                    placeholder="850000"
                                    min="0"
                                    required>
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                Tipe Kos <span class="text-danger">*</span>
                            </label>
                            <select 
                                name="tipe" 
                                class="form-select @error('tipe') is-invalid @enderror" 
                                required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="Putra" {{ old('tipe') == 'Putra' ? 'selected' : '' }}>Putra</option>
                                <option value="Putri" {{ old('tipe') == 'Putri' ? 'selected' : '' }}>Putri</option>
                                <option value="Campur" {{ old('tipe') == 'Campur' ? 'selected' : '' }}>Campur</option>
                            </select>
                            @error('tipe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Fasilitas --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Fasilitas <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="fasilitas" 
                            class="form-control @error('fasilitas') is-invalid @enderror" 
                            value="{{ old('fasilitas') }}"
                            placeholder="Contoh: AC, WiFi, KM Dalam, Parkir Motor"
                            required>
                        @error('fasilitas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Pisahkan dengan koma, contoh: AC, WiFi, KM Dalam</small>
                    </div>

                    {{-- Foto --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Foto Kos (opsional)</label>
                        <input 
                            type="file" 
                            name="foto" 
                            class="form-control @error('foto') is-invalid @enderror" 
                            accept="image/*"
                            id="inputFoto">
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Format: JPG, PNG. Maksimal: 2MB</small>

                        {{-- Preview Foto --}}
                        <div class="mt-2">
                            <img id="previewFoto" src="#" alt="Preview" class="img-thumbnail d-none" width="200">
                        </div>
                    </div>

                    <hr>

                    {{-- Tombol --}}
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('kos.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Kos
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- Script Preview Foto --}}
<script>
    document.getElementById('inputFoto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                const preview = document.getElementById('previewFoto');
                preview.src = ev.target.result;
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection