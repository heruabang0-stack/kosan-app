@extends('layouts.app')

@section('title', 'Edit Data Kos - ' . $kos->nama_kos)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="mb-3">
            <a href="{{ route('admin.kos.index') }}" class="btn btn-outline-light btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kos
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-dark py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-pencil-square me-2"></i> Edit Data Kos: {{ $kos->nama_kos }}
                </h5>
            </div>
            <div class="card-body p-4">

                @if ($errors->any())
                    <div class="alert alert-danger py-2 small">
                        <strong>Periksa kembali data Anda:</strong>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.kos.update', $kos->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Nama Kos --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small">
                            Nama Kos <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="nama_kos" 
                            class="form-control @error('nama_kos') is-invalid @enderror" 
                            value="{{ old('nama_kos', $kos->nama_kos) }}" 
                            required>
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small">
                            Alamat Lengkap <span class="text-danger">*</span>
                        </label>
                        <textarea 
                            name="alamat" 
                            class="form-control @error('alamat') is-invalid @enderror" 
                            rows="2" 
                            required>{{ old('alamat', $kos->alamat) }}</textarea>
                    </div>

                    {{-- LOKASI MAPS & KOORDINAT INDONESIA --}}
                    <div class="card bg-light border-0 p-3 mb-3">
                        <label class="form-label fw-bold small mb-1">
                            <i class="bi bi-pin-map-fill text-danger me-1"></i> Titik Lokasi Peta Indonesia (Klik / geser pin untuk memperbarui lokasi)
                        </label>
                        <small class="text-muted mb-2 d-block">Peta terkunci di wilayah Indonesia. Klik lokasi kos untuk mengubah koordinat.</small>
                        
                        <div id="mapPicker" style="height: 280px; width: 100%; border-radius: 10px;" class="mb-2 shadow-sm"></div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold">Latitude</label>
                                <input type="text" name="latitude" id="inputLat" class="form-control form-control-sm" value="{{ old('latitude', $kos->latitude ?? '-7.7700000') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold">Longitude</label>
                                <input type="text" name="longitude" id="inputLng" class="form-control form-control-sm" value="{{ old('longitude', $kos->longitude ?? '110.3750000') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Harga & Tipe --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small">
                                Harga Sewa / Bulan <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input 
                                    type="number" 
                                    name="harga" 
                                    class="form-control @error('harga') is-invalid @enderror" 
                                    value="{{ old('harga', $kos->harga) }}" 
                                    min="0"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small">
                                Tipe Kos <span class="text-danger">*</span>
                            </label>
                            <select name="tipe" class="form-select @error('tipe') is-invalid @enderror" required>
                                <option value="Putra" {{ old('tipe', $kos->tipe) == 'Putra' ? 'selected' : '' }}>Putra</option>
                                <option value="Putri" {{ old('tipe', $kos->tipe) == 'Putri' ? 'selected' : '' }}>Putri</option>
                                <option value="Campur" {{ old('tipe', $kos->tipe) == 'Campur' ? 'selected' : '' }}>Campur</option>
                            </select>
                        </div>
                    </div>

                    {{-- Fasilitas --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small">
                            Fasilitas <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="fasilitas" 
                            class="form-control @error('fasilitas') is-invalid @enderror" 
                            value="{{ old('fasilitas', $kos->fasilitas) }}" 
                            required>
                        <small class="text-muted">Pisahkan setiap fasilitas dengan tanda koma.</small>
                    </div>

                    {{-- Foto --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold small">Foto Kos</label>
                        @if($kos->foto)
                            <div class="mb-2">
                                <img src="{{ asset('foto/' . $kos->foto) }}" alt="Foto Lama" class="img-thumbnail rounded" width="160">
                                <span class="small text-muted d-block mt-1">Foto saat ini</span>
                            </div>
                        @endif
                        <input 
                            type="file" 
                            name="foto" 
                            class="form-control @error('foto') is-invalid @enderror" 
                            accept="image/*"
                            id="inputFoto">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto kos.</small>

                        <div class="mt-2">
                            <img id="previewFoto" src="#" alt="Preview Foto Baru" class="img-thumbnail d-none" width="160">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between pt-3 border-top">
                        <a href="{{ route('admin.kos.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-warning fw-bold">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan Kos
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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

    const boundsIndonesia = L.latLngBounds(
        L.latLng(-11.5, 94.5),
        L.latLng(6.5, 141.5)
    );

    const currentLat = parseFloat(document.getElementById('inputLat').value) || -7.7700000;
    const currentLng = parseFloat(document.getElementById('inputLng').value) || 110.3750000;

    const map = L.map('mapPicker', {
        center: [currentLat, currentLng],
        zoom: 15,
        minZoom: 5,
        maxZoom: 18,
        maxBounds: boundsIndonesia,
        maxBoundsViscosity: 1.0
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let marker = L.marker([currentLat, currentLng], { draggable: true }).addTo(map);

    function updateInputs(lat, lng) {
        document.getElementById('inputLat').value = lat.toFixed(7);
        document.getElementById('inputLng').value = lng.toFixed(7);
    }

    marker.on('dragend', function (e) {
        const pos = e.target.getLatLng();
        updateInputs(pos.lat, pos.lng);
    });

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateInputs(e.latlng.lat, e.latlng.lng);
    });
</script>
@endsection
