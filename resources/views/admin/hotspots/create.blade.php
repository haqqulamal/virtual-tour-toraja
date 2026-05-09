@extends('layouts.admin')

@section('title', 'Buat Hotspot Baru - Admin Panel')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="fas fa-plus-circle text-success"></i> Buat Hotspot Baru</h2>
    </div>
</div>

<form action="{{ route('admin.hotspots.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header border-success">
                    <h5 class="mb-0"><i class="fas fa-crosshairs"></i> Informasi Hotspot</h5>
                </div>
                <div class="card-body">
                    <!-- Scene Selection -->
                    <div class="mb-3">
                        <label for="scene_id" class="form-label"><strong>Pilih Adegan</strong> <span class="text-danger">*</span></label>
                        <select class="form-select @error('scene_id') is-invalid @enderror" id="scene_id" name="scene_id" required>
                            <option value="">-- Pilih Adegan --</option>
                            @foreach($scenes as $scene)
                                <option value="{{ $scene->id }}" {{ old('scene_id') == $scene->id ? 'selected' : '' }}>{{ $scene->title }}</option>
                            @endforeach
                        </select>
                        @error('scene_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Type Selection -->
                    <div class="mb-3">
                        <label class="form-label"><strong>Tipe Hotspot</strong> <span class="text-danger">*</span></label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="typeInfo" name="type" value="info" {{ old('type', 'info') === 'info' ? 'checked' : '' }} onchange="toggleTypeFields()">
                                <label class="form-check-label" for="typeInfo">
                                    <i class="fas fa-info-circle text-info"></i> Informasi
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="typeScene" name="type" value="scene" {{ old('type') === 'scene' ? 'checked' : '' }} onchange="toggleTypeFields()">
                                <label class="form-check-label" for="typeScene">
                                    <i class="fas fa-door-open text-warning"></i> Navigasi Adegan
                                </label>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <strong>Informasi:</strong> Menampilkan pop-up dengan teks dan gambar<br>
                            <strong>Navigasi:</strong> Membawa pengunjung ke adegan lain saat diklik
                        </small>
                    </div>

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label"><strong>Judul Hotspot</strong> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Menara Penjaga" required>
                        @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Content (Info type only) -->
                    <div class="mb-3" id="contentField" style="display: {{ old('type', 'info') === 'info' ? 'block' : 'none' }};">
                        <label for="content" class="form-label"><strong>Konten Info</strong></label>
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="4" placeholder="Jelaskan tentang lokasi ini...">{{ old('content') }}</textarea>
                        <small class="text-muted d-block mt-2">Akan ditampilkan dalam pop-up saat hotspot diklik</small>
                        @error('content')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image Upload (Info type only) -->
                    <div class="mb-3" id="imageField" style="display: {{ old('type', 'info') === 'info' ? 'block' : 'none' }};">
                        <label for="image_path" class="form-label"><strong>Gambar untuk Pop-up</strong></label>
                        <div class="input-group">
                            <input type="file" class="form-control @error('image_path') is-invalid @enderror" id="image_path" name="image_path" accept=".jpg,.jpeg,.png">
                            <span class="input-group-text"><i class="fas fa-upload"></i></span>
                        </div>
                        <small class="text-muted d-block mt-2">Format: JPG, PNG | Opsional - jika ada, gambar akan ditampilkan di pop-up</small>
                        @error('image_path')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image Preview -->
                    <div id="imagePreview" class="mb-3" style="display: none;">
                        <small class="d-block mb-2">Preview:</small>
                        <img id="previewImg" src="" alt="Preview" style="max-width: 100%; height: auto; border-radius: 4px; max-height: 200px;">
                    </div>

                    <!-- Target Scene (Scene type only) -->
                    <div class="mb-3" id="targetSceneField" style="display: {{ old('type') === 'scene' ? 'block' : 'none' }};">
                        <label for="target_scene_id" class="form-label"><strong>Adegan Tujuan</strong> <span class="text-danger">*</span></label>
                        <select class="form-select @error('target_scene_id') is-invalid @enderror" id="target_scene_id" name="target_scene_id">
                            <option value="">-- Pilih Adegan Tujuan --</option>
                            @foreach($scenes as $scene)
                                <option value="{{ $scene->id }}" {{ old('target_scene_id') == $scene->id ? 'selected' : '' }}>{{ $scene->title }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-2">Adegan yang akan ditampilkan ketika pengunjung mengklik hotspot ini</small>
                        @error('target_scene_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-success">
                    <h5 class="mb-0"><i class="fas fa-map-pin"></i> Koordinat Posisi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pitch" class="form-label"><strong>Pitch (Vertikal)</strong> <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('pitch') is-invalid @enderror" id="pitch" name="pitch" step="0.1" value="{{ old('pitch', 0) }}" min="-90" max="90" required>
                            <small class="text-muted d-block mt-2">Rentang: -90° (bawah) hingga 90° (atas)</small>
                            <small class="text-warning"><i class="fas fa-info-circle"></i> 0° = horizontal</small>
                            @error('pitch')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="yaw" class="form-label"><strong>Yaw (Horizontal)</strong> <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('yaw') is-invalid @enderror" id="yaw" name="yaw" step="0.1" value="{{ old('yaw', 0) }}" min="0" max="360" required>
                            <small class="text-muted d-block mt-2">Rentang: 0° hingga 360°</small>
                            <small class="text-info"><i class="fas fa-compass"></i> 0°=Utara, 90°=Timur, 180°=Selatan, 270°=Barat</small>
                            @error('yaw')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="fas fa-lightbulb"></i> <strong>Butuh bantuan menemukan koordinat?</strong><br>
                        Gunakan <strong>Pannellum Editor</strong> untuk mencari koordinat yang tepat: 
                        <a href="https://pannellum.org/documentation/examples/editor.html" target="_blank" class="text-info">pannellum.org/editor</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Form Actions -->
            <div class="card mb-4 sticky-top" style="top: 20px;">
                <div class="card-header border-success">
                    <h5 class="mb-0"><i class="fas fa-save"></i> Aksi</h5>
                </div>
                <div class="card-body d-flex flex-column gap-2">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-save"></i> Simpan Hotspot
                    </button>
                    <a href="{{ route('admin.hotspots.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card">
                <div class="card-header border-success">
                    <h5 class="mb-0"><i class="fas fa-question-circle"></i> Bantuan</h5>
                </div>
                <div class="card-body small">
                    <p><strong>Tipe Hotspot</strong></p>
                    <p><strong>Info:</strong> Menampilkan kotak pop-up dengan teks panjang dan gambar opsional.</p>
                    <p><strong>Navigasi:</strong> Mengarahkan pengunjung ke adegan panorama lain.</p>

                    <hr>

                    <p><strong>Koordinat</strong></p>
                    <p>Gunakan Pannellum Editor untuk mendapatkan nilai pitch dan yaw yang akurat untuk posisi hotspot di panorama.</p>

                    <hr>

                    <p><strong>Gambar Pop-up</strong></p>
                    <p>Opsional. Jika diunggah, gambar akan muncul di atas teks dalam pop-up info.</p>
                </div>
            </div>
        </div>
    </div>
</form>

@section('extra_js')
<script>
function toggleTypeFields() {
    const type = document.querySelector('input[name="type"]:checked').value;
    
    // Show/hide content field
    document.getElementById('contentField').style.display = type === 'info' ? 'block' : 'none';
    
    // Show/hide image field
    document.getElementById('imageField').style.display = type === 'info' ? 'block' : 'none';
    
    // Show/hide target scene field
    document.getElementById('targetSceneField').style.display = type === 'scene' ? 'block' : 'none';
    
    // Clear validation if hiding field
    if (type === 'scene') {
        document.getElementById('content').removeAttribute('required');
    } else {
        // Can add required back if needed
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Image preview
    const imageInput = document.getElementById('image_path');
    imageInput?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('previewImg').src = event.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Initial toggle
    toggleTypeFields();
});
</script>
@endsection
@endsection
                    @error('yaw') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required onchange="updateTypeFields()">
                    <option value="info">Information Point</option>
                    <option value="scene">Scene Navigation</option>
                    <option value="artifact">Artifact Link</option>
                </select>
                @error('type') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3" id="linkedSceneField" style="display: none;">
                <label for="linked_scene_id" class="form-label">Link to Scene</label>
                <select class="form-select @error('linked_scene_id') is-invalid @enderror" id="linked_scene_id" name="linked_scene_id">
                    <option value="">Choose Scene</option>
                    @foreach($scenes as $scene)
                        <option value="{{ $scene->id }}">{{ $scene->title }}</option>
                    @endforeach
                </select>
                @error('linked_scene_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3" id="artifactField" style="display: none;">
                <label for="artifact_id" class="form-label">Link to Artifact</label>
                <select class="form-select @error('artifact_id') is-invalid @enderror" id="artifact_id" name="artifact_id">
                    <option value="">Choose Artifact</option>
                    @foreach($artifacts as $artifact)
                        <option value="{{ $artifact->id }}">{{ $artifact->name }}</option>
                    @endforeach
                </select>
                @error('artifact_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> {{ __('messages.save') }}
                </button>
                <a href="{{ route('admin.hotspots.index') }}" class="btn btn-secondary">
                    {{ __('messages.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function updateTypeFields() {
    const type = document.getElementById('type').value;
    document.getElementById('linkedSceneField').style.display = type === 'scene' ? 'block' : 'none';
    document.getElementById('artifactField').style.display = type === 'artifact' ? 'block' : 'none';
}
updateTypeFields();
</script>
@endsection
