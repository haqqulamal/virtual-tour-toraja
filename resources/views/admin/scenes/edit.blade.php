@extends('layouts.admin')

@section('title', 'Edit Adegan - Admin Panel')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="fas fa-edit text-success"></i> Edit Adegan: {{ $scene->title }}</h2>
    </div>
</div>

<form action="{{ route('admin.scenes.update', $scene->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
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
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $scene->title) }}" placeholder="Contoh: Tongkonan Layuk" required>
                        @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label"><strong>Deskripsi</strong> <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" placeholder="Jelaskan tentang adegan ini..." required>{{ old('description', $scene->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Order -->
                    <div class="mb-3">
                        <label for="order" class="form-label"><strong>Urutan Tampilan</strong></label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $scene->order) }}" min="0" placeholder="0">
                        <small class="text-muted">Adegan dengan angka lebih kecil akan tampil lebih dahulu</small>
                        @error('order')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Active Checkbox -->
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $scene->is_active) ? 'checked' : '' }}>
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
                    <!-- Current Equirectangular Image -->
                    @if($scene->image_path)
                        <div class="mb-3 p-3 bg-dark rounded">
                            <small class="text-muted d-block mb-2"><i class="fas fa-check-circle text-success"></i> Gambar Panorama Saat Ini:</small>
                            <img src="{{ $scene->getImageUrl() }}" alt="Current Panorama" style="max-width: 100%; height: auto; border-radius: 4px; max-height: 250px;">
                        </div>
                    @endif

                    <!-- Upload new Equirectangular Image -->
                    <div class="mb-3">
                        <label for="image_path" class="form-label"><strong>Ganti Gambar Equirectangular</strong></label>
                        <div class="input-group">
                            <input type="file" class="form-control @error('image_path') is-invalid @enderror" id="image_path" name="image_path" accept=".jpg,.jpeg,.png">
                            <span class="input-group-text"><i class="fas fa-upload"></i></span>
                        </div>
                        <small class="text-muted d-block mt-2">Format: JPG, PNG | Kosongkan jika tidak ingin mengubah</small>
                        <small class="text-warning d-block"><i class="fas fa-exclamation-triangle"></i> Maksimal 10MB</small>
                        @error('image_path')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image Preview -->
                    <div id="imagePreview" class="mb-3" style="display: none;">
                        <small class="d-block mb-2">Preview Gambar Baru:</small>
                        <img id="previewImg" src="" alt="Preview" style="max-width: 100%; height: auto; border-radius: 4px; max-height: 300px;">
                    </div>

                    <!-- Current Thumbnail -->
                    @if($scene->thumbnail)
                        <div class="mb-3 p-3 bg-dark rounded">
                            <small class="text-muted d-block mb-2"><i class="fas fa-check-circle text-success"></i> Thumbnail Saat Ini:</small>
                            <img src="{{ $scene->getThumbnailUrl() }}" alt="Current Thumbnail" style="width: 280px; height: 200px; object-fit: cover; border-radius: 4px;">
                        </div>
                    @endif

                    <!-- Upload new Thumbnail -->
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label"><strong>Ganti Gambar Thumbnail</strong></label>
                        <div class="input-group">
                            <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" id="thumbnail" name="thumbnail" accept=".jpg,.jpeg,.png">
                            <span class="input-group-text"><i class="fas fa-upload"></i></span>
                        </div>
                        <small class="text-muted d-block mt-2">Format: JPG, PNG | Kosongkan jika tidak ingin mengubah</small>
                        @error('thumbnail')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Thumbnail Preview -->
                    <div id="thumbnailPreview" class="mb-3" style="display: none;">
                        <small class="d-block mb-2">Preview Thumbnail Baru:</small>
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
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.scenes.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    
                    <!-- Delete Button -->
                    <button type="button" class="btn btn-danger w-100 mt-2" data-bs-toggle="modal" data-bs-target="#deleteConfirm">
                        <i class="fas fa-trash"></i> Hapus Adegan
                    </button>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card">
                <div class="card-header border-success">
                    <h5 class="mb-0"><i class="fas fa-question-circle"></i> Informasi</h5>
                </div>
                <div class="card-body small">
                    <p><strong>ID Adegan:</strong> #{{ $scene->id }}</p>
                    <p><strong>Dibuat:</strong> {{ $scene->created_at->format('d M Y H:i') }}</p>
                    <p><strong>Diperbarui:</strong> {{ $scene->updated_at->format('d M Y H:i') }}</p>
                    <p><strong>Total Hotspot:</strong> {{ $scene->hotspots->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirm" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark border-danger">
            <div class="modal-header border-danger">
                <h5 class="modal-title">Hapus Adegan?</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin menghapus adegan <strong>{{ $scene->title }}</strong>?</p>
                <p class="text-warning"><i class="fas fa-exclamation-triangle"></i> <small>Menghapus adegan akan juga menghapus semua hotspot yang terhubung. Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.scenes.destroy', $scene->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

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
