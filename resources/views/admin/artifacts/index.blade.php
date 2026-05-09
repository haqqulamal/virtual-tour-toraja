@extends('layouts.admin')

@section('title', 'Kelola Artefak - Admin Panel')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="fas fa-box text-success"></i> Kelola Artefak Budaya</h2>
            <a href="{{ route('admin.artifacts.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Tambah Artefak Baru
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="border-bottom">
                <tr>
                    <th style="width: 20%;">Gambar</th>
                    <th style="width: 20%;">Judul (ID)</th>
                    <th style="width: 20%;">Kategori</th>
                    <th style="width: 15%;">Unggulan</th>
                    <th style="width: 25%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($artifacts as $artifact)
                    <tr>
                        <td>
                            @if($artifact->image_path)
                                <img src="{{ $artifact->getImageUrl() }}" alt="{{ $artifact->title_id }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                            @else
                                <div style="width: 60px; height: 60px; background: #2d9b6f; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-image text-white"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $artifact->title_id }}</strong>
                            <br>
                            <small class="text-muted">{{ $artifact->title_en }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $artifact->category->name_id }}</span>
                        </td>
                        <td>
                            <form action="{{ route('admin.artifacts.update', $artifact->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <div class="form-check form-switch" style="cursor: pointer;">
                                    <input class="form-check-input" type="checkbox" id="featured_{{ $artifact->id }}" name="is_featured" value="1" {{ $artifact->is_featured ? 'checked' : '' }} onchange="this.form.submit();" style="cursor: pointer;">
                                    <label class="form-check-label" for="featured_{{ $artifact->id }}" style="cursor: pointer;">
                                        {{ $artifact->is_featured ? 'Ya' : 'Tidak' }}
                                    </label>
                                </div>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('admin.artifacts.edit', $artifact->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $artifact->id }}" title="Hapus">
                                <i class="fas fa-trash"></i> Hapus
                            </button>

                            <!-- Delete Confirmation Modal -->
                            <div class="modal fade" id="deleteModal{{ $artifact->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content bg-dark border-danger">
                                        <div class="modal-header border-danger">
                                            <h5 class="modal-title">Hapus Artefak?</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus artefak <strong>{{ $artifact->title_id }}</strong>?</p>
                                            <p class="text-warning"><i class="fas fa-exclamation-triangle"></i> <small>Tindakan ini tidak dapat dibatalkan.</small></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('admin.artifacts.destroy', $artifact->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="fas fa-inbox text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">Belum ada artefak. <a href="{{ route('admin.artifacts.create') }}">Buat yang pertama</a></p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($artifacts->hasPages())
    <div class="mt-4 d-flex justify-content-center">
        {{ $artifacts->links() }}
    </div>
@endif
@endsection
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $artifacts->links() }}
</div>
@endsection
