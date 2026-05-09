<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSceneRequest;
use App\Http\Requests\UpdateSceneRequest;
use App\Models\Scene;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SceneController extends Controller
{
    /**
     * Display a listing of scenes
     */
    public function index(): View
    {
        $scenes = Scene::orderBy('order')->paginate(15);
        return view('admin.scenes.index', compact('scenes'));
    }

    /**
     * Show the form for creating a new scene
     */
    public function create(): View
    {
        return view('admin.scenes.create');
    }

    /**
     * Store a newly created scene in storage
     */
    public function store(StoreSceneRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')
                ->store('scenes', 'public');
            $data['image_path'] = $path;
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')
                ->store('scenes/thumbnails', 'public');
            $data['thumbnail'] = $path;
        }

        Scene::create($data);

        return redirect()
            ->route('admin.scenes.index')
            ->with('success', __('messages.scene_created_successfully'));
    }

    /**
     * Show the form for editing the specified scene
     */
    public function edit(Scene $scene): View
    {
        return view('admin.scenes.edit', compact('scene'));
    }

    /**
     * Update the specified scene in storage
     */
    public function update(UpdateSceneRequest $request, Scene $scene): RedirectResponse
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image_path')) {
            // Delete old image
            if ($scene->image_path) {
                Storage::disk('public')->delete($scene->image_path);
            }
            $path = $request->file('image_path')
                ->store('scenes', 'public');
            $data['image_path'] = $path;
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($scene->thumbnail) {
                Storage::disk('public')->delete($scene->thumbnail);
            }
            $path = $request->file('thumbnail')
                ->store('scenes/thumbnails', 'public');
            $data['thumbnail'] = $path;
        }

        $scene->update($data);

        return redirect()
            ->route('admin.scenes.index')
            ->with('success', __('messages.scene_updated_successfully'));
    }

    /**
     * Delete the specified scene
     */
    public function destroy(Scene $scene): RedirectResponse
    {
        // Delete associated images
        if ($scene->image_path) {
            Storage::disk('public')->delete($scene->image_path);
        }
        if ($scene->thumbnail) {
            Storage::disk('public')->delete($scene->thumbnail);
        }

        // Delete all associated hotspots
        $scene->hotspots()->delete();

        $scene->delete();

        return redirect()
            ->route('admin.scenes.index')
            ->with('success', __('messages.scene_deleted_successfully'));
    }

    /**
     * Reorder scenes
     */
    public function reorder(Request $request): RedirectResponse
    {
        $request->validate([
            'scenes' => 'required|array',
            'scenes.*' => 'required|integer|exists:scenes,id',
        ]);

        foreach ($request->input('scenes') as $index => $sceneId) {
            Scene::where('id', $sceneId)->update(['order' => $index + 1]);
        }

        return redirect()
            ->route('admin.scenes.index')
            ->with('success', __('messages.scenes_reordered_successfully'));
    }
}
