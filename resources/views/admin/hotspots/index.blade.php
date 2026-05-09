@extends('layouts.admin')

@section('title', 'Kelola Hotspot - Admin Panel')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="fas fa-crosshairs text-success"></i> Kelola Hotspot</h2>
            <a href="{{ route('admin.hotspots.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Tambah Hotspot Baru
            </a>
        </div>
    </div>
</div>

<!-- Scene Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="sceneFilter" class="form-label"><strong>Filter Berdasarkan Adegan</strong></label>
                        <select id="sceneFilter" class="form-select">
                            <option value="">-- Semua Adegan --</option>
                            @foreach($scenes as $scene)
                                <option value="{{ $scene->id }}">{{ $scene->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button class="btn btn-outline-success" id="filterBtn">
                            <i class="fas fa-filter"></i> Terapkan Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="border-bottom">
                <tr>
                    <th style="width: 25%;">Adegan</th>
                    <th style="width: 15%;">Tipe</th>
                    <th style="width: 25%;">Judul</th>
                    <th style="width: 20%;">Koordinat</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hotspots as $hotspot)
                    <tr>
                        <td>
                            <strong>{{ $hotspot->scene->title }}</strong>
                            <br>
                            <small class="text-muted">#{{ $hotspot->scene->id }}</small>
                        </td>
                        <td>
                            @if($hotspot->type === 'info')
                                <span class="badge bg-info"><i class="fas fa-info-circle"></i> Info</span>
                            @else
                                <span class="badge bg-warning"><i class="fas fa-door-open"></i> Adegan</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $hotspot->title }}</strong>
                            @if($hotspot->type === 'scene' && $hotspot->targetScene)
                                <br>
                                <small class="text-muted">→ {{ $hotspot->targetScene->title }}</small>
                            @endif
                        </td>
                        <td>
                            <small>
                                <strong>Pitch:</strong> {{ number_format($hotspot->pitch, 2) }}°<br>
                                <strong>Yaw:</strong> {{ number_format($hotspot->yaw, 2) }}°
                            </small>
                        </td>
                        <td>
                            <a href="{{ route('admin.hotspots.edit', $hotspot->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $hotspot->id }}" title="Hapus">
                                <i class="fas fa-trash"></i> Hapus
                            </button>

                            <!-- Delete Confirmation Modal -->
                            <div class="modal fade" id="deleteModal{{ $hotspot->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content bg-dark border-danger">
                                        <div class="modal-header border-danger">
                                            <h5 class="modal-title">Hapus Hotspot?</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus hotspot <strong>{{ $hotspot->title }}</strong>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('admin.hotspots.destroy', $hotspot->id) }}" method="POST" style="display:inline;">
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
                            <p class="text-muted mt-2">Belum ada hotspot. <a href="{{ route('admin.hotspots.create') }}">Buat yang pertama</a></p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($hotspots->hasPages())
    <div class="mt-4 d-flex justify-content-center">
        {{ $hotspots->links() }}
    </div>
@endif

@section('extra_js')
<script>
document.getElementById('filterBtn')?.addEventListener('click', function() {
    const sceneId = document.getElementById('sceneFilter').value;
    if (sceneId) {
        window.location.href = `{{ route('admin.hotspots.by-scene', '') }}/${sceneId}`;
    } else {
        window.location.href = `{{ route('admin.hotspots.index') }}`;
    }
});
</script>
@endsection
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $hotspots->links() }}
</div>
@endsection
