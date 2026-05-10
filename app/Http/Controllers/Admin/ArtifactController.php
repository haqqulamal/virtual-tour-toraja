<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArtifactRequest;
use App\Http\Requests\UpdateArtifactRequest;
use App\Models\Artifact;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ArtifactController extends Controller
{
    /**
     * Display a listing of artifacts
     */
    public function index(): View
    {
        $artifacts = Artifact::with('category')->paginate(20);
        return view('admin.artifacts.index', compact('artifacts'));
    }

    /**
     * Show the form for creating a new artifact
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.artifacts.create', compact('categories'));
    }

    /**
     * Store a newly created artifact in storage
     */
    public function store(StoreArtifactRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')
                ->store('artifacts', 'public');
            $data['image_path'] = $path;
        }

        Artifact::create($data);

        return redirect()
            ->route('admin.artifacts.index')
            ->with('success', __('messages.artifact_created_successfully'));
    }

    /**
     * Show the form for editing the specified artifact
     */
    public function edit(Artifact $artifact): View
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.artifacts.edit', compact('artifact', 'categories'));
    }

    /**
     * Update the specified artifact in storage
     */
    public function update(UpdateArtifactRequest $request, Artifact $artifact): RedirectResponse
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image_path')) {
            // Delete old image
            if ($artifact->image_path) {
                Storage::disk('public')->delete($artifact->image_path);
            }
            $path = $request->file('image_path')
                ->store('artifacts', 'public');
            $data['image_path'] = $path;
        }

        $artifact->update($data);

        return redirect()
            ->route('admin.artifacts.index')
            ->with('success', __('messages.artifact_updated_successfully'));
    }

    /**
     * Delete the specified artifact
     */
    public function destroy(Artifact $artifact): RedirectResponse
    {
        // Delete associated image
        if ($artifact->image_path) {
            Storage::disk('public')->delete($artifact->image_path);
        }

        $artifact->delete();

        return redirect()
            ->route('admin.artifacts.index')
            ->with('success', __('messages.artifact_deleted_successfully'));
    }
}
