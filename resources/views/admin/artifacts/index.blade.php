@extends('layouts.admin')

@section('title', __('messages.manage_artifacts'))

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>{{ __('messages.manage_artifacts') }}</h2>
            <a href="{{ route('admin.artifacts.create') }}" class="btn btn-success">
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
                    <th>Name</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($artifacts as $artifact)
                    <tr>
                        <td>{{ $artifact->name }}</td>
                        <td>{{ $artifact->category->name }}</td>
                        <td>
                            <span class="badge {{ $artifact->is_published ? 'bg-success' : 'bg-warning' }}">
                                {{ $artifact->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.artifacts.edit', $artifact->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.artifacts.destroy', $artifact->id) }}" method="POST" style="display:inline;">
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
    {{ $artifacts->links() }}
</div>
@endsection
