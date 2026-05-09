@extends('layouts.admin')

@section('title', __('messages.manage_categories'))

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>{{ __('messages.manage_categories') }}</h2>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
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
                    <th>{{ __('messages.name') ?? 'Nama' }}</th>
                    <th>Slug</th>
                    <th>Artifacts</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td><span class="badge bg-info">{{ $category->artifacts_count }}</span></td>
                        <td>
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline;">
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
    {{ $categories->links() }}
</div>
@endsection
