@extends('layouts.admin')

@section('title', __('messages.manage_hotspots'))

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>{{ __('messages.manage_hotspots') }}</h2>
            <a href="{{ route('admin.hotspots.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> {{ __('messages.add_new') }}
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="border-bottom">
                <tr>
                    <th>Title</th>
                    <th>Scene</th>
                    <th>Type</th>
                    <th>Position (Pitch, Yaw)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hotspots as $hotspot)
                    <tr>
                        <td>{{ $hotspot->title }}</td>
                        <td>{{ $hotspot->scene->title }}</td>
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
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
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
