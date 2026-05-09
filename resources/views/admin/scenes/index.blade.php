@extends('layouts.admin')

@section('title', 'Kelola Adegan - Admin Panel')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="fas fa-camera text-success"></i> Kelola Adegan (Scenes)</h2>
            <a href="{{ route('admin.scenes.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Tambah Adegan Baru
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="border-bottom">
                <tr>
                    <th style="width: 40%;">Judul</th>
                    <th style="width: 15%;">Urutan</th>
                    <th style="width: 15%;">Status</th>
                    <th style="width: 30%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($scenes as $scene)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($scene->thumbnail)
                                    <img src="{{ $scene->getThumbnailUrl() }}" alt="{{ $scene->title }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <div style="width: 50px; height: 50px; background: #2d9b6f; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-image text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <strong>{{ $scene->title }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($scene->description, 50) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $scene->order }}</span>
                        </td>
                        <td>
                            <form action="{{ route('admin.scenes.update', $scene->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <div class="form-check form-switch" style="cursor: pointer;">
                                    <input class="form-check-input" type="checkbox" id="active_{{ $scene->id }}" name="is_active" value="1" {{ $scene->is_active ? 'checked' : '' }} onchange="this.form.submit();" style="cursor: pointer;">
                                    <label class="form-check-label" for="active_{{ $scene->id }}" style="cursor: pointer;">
                                        {{ $scene->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </label>
                                </div>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('admin.scenes.edit', $scene->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $scene->id }}" title="Hapus">
                                <i class="fas fa-trash"></i> Hapus
                            </button>

                            <!-- Delete Confirmation Modal -->
                            <div class="modal fade" id="deleteModal{{ $scene->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content bg-dark border-success">
                                        <div class="modal-header border-success">
                                            <h5 class="modal-title">Konfirmasi Penghapusan</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus adegan <strong>{{ $scene->title }}</strong>?</p>
                                            <p class="text-warning"><small><i class="fas fa-exclamation-triangle"></i> Tindakan ini tidak dapat dibatalkan.</small></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('admin.scenes.destroy', $scene->id) }}" method="POST" style="display:inline;">
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
                        <td colspan="4" class="text-center py-4">
                            <i class="fas fa-inbox text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">Belum ada adegan. <a href="{{ route('admin.scenes.create') }}">Buat yang pertama</a></p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($scenes->hasPages())
    <div class="mt-4 d-flex justify-content-center">
        {{ $scenes->links() }}
    </div>
@endif
@endsection
