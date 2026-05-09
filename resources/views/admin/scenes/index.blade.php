@extends('layouts.admin')

@section('title', __('messages.manage_scenes'))

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>{{ __('messages.manage_scenes') }}</h2>
            <a href="{{ route('admin.scenes.create') }}" class="btn btn-success">
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
                    <th>{{ __('messages.title') }}</th>
                    <th>{{ __('messages.location') }}</th>
                    <th>Status</th>
                    <th>Order</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($scenes as $scene)
                    <tr>
                        <td>{{ $scene->title }}</td>
                        <td>{{ $scene->location }}</td>
                        <td>
                            <span class="badge {{ $scene->is_published ? 'bg-success' : 'bg-warning' }}">
                                {{ $scene->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td>{{ $scene->order }}</td>
                        <td>
                            <a href="{{ route('admin.scenes.edit', $scene->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.scenes.destroy', $scene->id) }}" method="POST" style="display:inline;">
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
    {{ $scenes->links() }}
</div>
@endsection
