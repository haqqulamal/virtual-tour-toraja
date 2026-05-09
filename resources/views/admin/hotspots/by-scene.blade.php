@extends('layouts.admin')

@section('title', 'Hotspots by Scene')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Hotspots: {{ $scene->title }}</h2>
            <a href="{{ route('admin.hotspots.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Add Hotspot
            </a>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <p><strong>Location:</strong> {{ $scene->location }}</p>
        <p><strong>Total Hotspots:</strong> {{ $hotspots->total() }}</p>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="border-bottom">
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Position</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hotspots as $hotspot)
                    <tr>
                        <td>{{ $hotspot->title }}</td>
                        <td>
                            <span class="badge {{ $hotspot->type === 'info' ? 'bg-info' : 'bg-success' }}">
                                {{ $hotspot->type }}
                            </span>
                        </td>
                        <td>{{ number_format($hotspot->pitch, 2) }}, {{ number_format($hotspot->yaw, 2) }}</td>
                        <td>
                            <a href="{{ route('admin.hotspots.edit', $hotspot->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.hotspots.destroy', $hotspot->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">No hotspots for this scene yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $hotspots->links() }}
</div>

<a href="{{ route('admin.hotspots.index') }}" class="btn btn-secondary mt-3">
    Back to All Hotspots
</a>
@endsection
