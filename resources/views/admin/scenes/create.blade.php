@extends('layouts.admin')

@section('title', 'Buat Adegan Baru - Admin Panel')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="fas fa-plus-circle text-success"></i> Buat Adegan Baru</h2>
    </div>
</div>

<form action="{{ route('admin.scenes.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header border-success">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Adegan</h5>
                </div>
                <div class="card-body">
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label"><strong>Judul Adegan</strong> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Tongkonan Layuk" required>
                        @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label"><strong>Deskripsi</strong> <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" placeholder="Jelaskan tentang adegan ini..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Order -->
                    <div class="mb-3">
                        <label for="order" class="form-label"><strong>Urutan Tampilan</strong></label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', 0) }}" min="0" placeholder="0">
                        <small class="text-muted">Adegan dengan angka lebih kecil akan tampil lebih dahulu</small>
                        @error('order')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Active Checkbox -->
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <strong>Aktifkan Adegan</strong>
                            </label>
                        </div>
                        <small class="text-muted d-block mt-2">Adegan yang tidak aktif tidak akan tampil di halaman depan</small>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header border-success">
                    <h5 class="mb-0"><i class="fas fa-image"></i> Gambar Panorama</h5>
                </div>
                <div class="card-body">
                    <!-- Equirectangular Image -->
                    <div class="mb-3">
                        <label for="image_path" class="form-label"><strong>Gambar Equirectangular</strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="file" class="form-control @error('image_path') is-invalid @enderror" id="image_path" name="image_path" accept=".jpg,.jpeg,.png" required>
                            <span class="input-group-text"><i class="fas fa-upload"></i></span>
                        </div>
                        <small class="text-muted d-block mt-2">Format: JPG, PNG | Format panorama: Equirectangular (2:1 ratio)</small>
                        <small class="text-warning d-block"><i class="fas fa-exclamation-triangle"></i> Maksimal 10MB</small>
                        @error('image_path')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image Preview -->
                    <div id="imagePreview" class="mb-3" style="display: none;">
                        <small class="d-block mb-2">Preview:</small>
                        <img id="previewImg" src="" alt="Preview" style="max-width: 100%; height: auto; border-radius: 4px; max-height: 300px;">
                    </div>

                    <!-- Thumbnail Image -->
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label"><strong>Gambar Thumbnail</strong></label>
                        <div class="input-group">
                            <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" id="thumbnail" name="thumbnail" accept=".jpg,.jpeg,.png">
                            <span class="input-group-text"><i class="fas fa-upload"></i></span>
                        </div>
                        <small class="text-muted d-block mt-2">Format: JPG, PNG | Dimensi: 280x200px (landscape)</small>
                        <small class="text-info d-block"><i class="fas fa-info-circle"></i> Opsional - jika tidak diunggah, preview pertama dari gambar panorama akan digunakan</small>
                        @error('thumbnail')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Thumbnail Preview -->
                    <div id="thumbnailPreview" class="mb-3" style="display: none;">
                        <small class="d-block mb-2">Preview Thumbnail:</small>
                        <img id="previewThumb" src="" alt="Thumbnail Preview" style="width: 280px; height: 200px; object-fit: cover; border-radius: 4px;">
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
                        <i class="fas fa-save"></i> Simpan Adegan
                    </button>
                    <a href="{{ route('admin.scenes.index') }}" class="btn btn-secondary w-100">
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
                    <p><strong>Format Panorama?</strong></p>
                    <p>Gunakan format equirectangular (2:1 aspect ratio). Contoh: 4096x2048px atau 2048x1024px.</p>
                    
                    <hr>
                    
                    <p><strong>Maksimal Ukuran?</strong></p>
                    <p>File gambar maksimal 10MB untuk kinerja optimal di browser.</p>
                    
                    <hr>
                    
                    <p><strong>Butuh Editor?</strong></p>
                    <p>Gunakan <strong>Pannellum Editor</strong> untuk menemukan koordinat hotspot yang tepat: <a href="https://pannellum.org/documentation/examples/editor.html" target="_blank" class="text-success">pannellum.org/editor</a></p>
                </div>
            </div>
        </div>
    </div>
</form>

@section('extra_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview for equirectangular image
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

    // Preview for thumbnail
    const thumbnailInput = document.getElementById('thumbnail');
    thumbnailInput?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('previewThumb').src = event.target.result;
                document.getElementById('thumbnailPreview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endsection
@endsection
