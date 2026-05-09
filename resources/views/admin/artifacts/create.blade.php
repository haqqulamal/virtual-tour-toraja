@extends('layouts.admin')

@section('title', 'Buat Artefak Baru - Admin Panel')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="fas fa-plus-circle text-success"></i> Buat Artefak Budaya Baru</h2>
    </div>
</div>

<form action="{{ route('admin.artifacts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header border-success">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Dasar</h5>
                </div>
                <div class="card-body">
                    <!-- Category -->
                    <div class="mb-3">
                        <label for="category_id" class="form-label"><strong>Kategori</strong> <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name_id }} ({{ $category->name_en }})
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Title ID (Indonesian) -->
                    <div class="mb-3">
                        <label for="title_id" class="form-label"><strong>Judul (Bahasa Indonesia)</strong> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title_id') is-invalid @enderror" id="title_id" name="title_id" value="{{ old('title_id') }}" placeholder="Contoh: Tongkonan Layuk" required>
                        @error('title_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Title EN (English) -->
                    <div class="mb-3">
                        <label for="title_en" class="form-label"><strong>Title (English)</strong> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title_en') is-invalid @enderror" id="title_en" name="title_en" value="{{ old('title_en') }}" placeholder="Example: Tongkonan Layuk" required>
                        @error('title_en')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description ID (Indonesian) -->
                    <div class="mb-3">
                        <label for="description_id" class="form-label"><strong>Deskripsi (Bahasa Indonesia)</strong></label>
                        <textarea class="form-control @error('description_id') is-invalid @enderror" id="description_id" name="description_id" rows="5" placeholder="Jelaskan tentang artefak ini...">{{ old('description_id') }}</textarea>
                        <small class="text-muted d-block mt-2">Deskripsi lengkap dengan latar belakang budaya dan sejarah</small>
                        @error('description_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description EN (English) -->
                    <div class="mb-3">
                        <label for="description_en" class="form-label"><strong>Description (English)</strong></label>
                        <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="5" placeholder="Describe this artifact...">{{ old('description_en') }}</textarea>
                        <small class="text-muted d-block mt-2">Full description with cultural and historical background</small>
                        @error('description_en')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Material -->
                    <div class="mb-3">
                        <label for="material" class="form-label"><strong>Material</strong></label>
                        <input type="text" class="form-control @error('material') is-invalid @enderror" id="material" name="material" value="{{ old('material') }}" placeholder="Contoh: Kayu, Bambu, Tenunan">
                        @error('material')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Featured -->
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                <strong><i class="fas fa-star text-warning"></i> Artefak Unggulan</strong>
                            </label>
                        </div>
                        <small class="text-muted d-block mt-2">Artefak unggulan akan ditampilkan di halaman utama koleksi</small>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-success">
                    <h5 class="mb-0"><i class="fas fa-image"></i> Gambar Artefak</h5>
                </div>
                <div class="card-body">
                    <!-- Image Upload -->
                    <div class="mb-3">
                        <label for="image_path" class="form-label"><strong>Unggah Gambar</strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="file" class="form-control @error('image_path') is-invalid @enderror" id="image_path" name="image_path" accept=".jpg,.jpeg,.png" required>
                            <span class="input-group-text"><i class="fas fa-upload"></i></span>
                        </div>
                        <small class="text-muted d-block mt-2">Format: JPG, PNG | Maksimal 5MB</small>
                        @error('image_path')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image Preview -->
                    <div id="imagePreview" class="mb-3" style="display: none;">
                        <small class="d-block mb-2">Preview:</small>
                        <img id="previewImg" src="" alt="Preview" style="max-width: 100%; height: auto; border-radius: 4px; max-height: 300px;">
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
                        <i class="fas fa-save"></i> Simpan Artefak
                    </button>
                    <a href="{{ route('admin.artifacts.index') }}" class="btn btn-secondary w-100">
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
                    <p><strong>Apa itu Artefak?</strong></p>
                    <p>Artefak adalah benda bersejarah atau budaya yang mewakili warisan budaya Toraja.</p>

                    <hr>

                    <p><strong>Bilingual</strong></p>
                    <p>Setiap artefak memiliki dua judul dan deskripsi (Indonesia dan English) untuk jangkauan lebih luas.</p>

                    <hr>

                    <p><strong>Unggulan</strong></p>
                    <p>Centang untuk menampilkan di bagian unggulan halaman koleksi.</p>

                    <hr>

                    <p><strong>Ukuran Gambar</strong></p>
                    <p>Gunakan gambar berkualitas tinggi, aspek rasio landscape direkomendasikan.</p>
                </div>
            </div>
        </div>
    </div>
</form>

@section('extra_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
});
</script>
@endsection
@endsection
