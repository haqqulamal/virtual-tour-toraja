<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHotspotRequest;
use App\Http\Requests\UpdateHotspotRequest;
use App\Models\Hotspot;
use App\Models\Scene;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class HotspotController extends Controller
{
    /**
     * Display a listing of hotspots
     */
    public function index(): View
    {
        $hotspots = Hotspot::with('scene', 'targetScene')
            ->paginate(20);
        return view('admin.hotspots.index', compact('hotspots'));
    }

    /**
     * Show the form for creating a new hotspot
     */
    public function create(): View
    {
        $scenes = Scene::orderBy('order')->get();
        return view('admin.hotspots.create', compact('scenes'));
    }

    /**
     * Store a newly created hotspot in storage
     */
    public function store(StoreHotspotRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Handle optional image upload for info hotspots
        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')
                ->store('hotspots', 'public');
            $data['image_path'] = $path;
        }

        Hotspot::create($data);

        return redirect()
            ->route('admin.hotspots.index')
            ->with('success', __('messages.hotspot_created_successfully'));
    }

    /**
     * Show the form for editing the specified hotspot
     */
    public function edit(Hotspot $hotspot): View
    {
        $scenes = Scene::orderBy('order')->get();
        return view('admin.hotspots.edit', compact('hotspot', 'scenes'));
    }

    /**
     * Update the specified hotspot in storage
     */
    public function update(UpdateHotspotRequest $request, Hotspot $hotspot): RedirectResponse
    {
        $data = $request->validated();

        // Handle optional image upload
        if ($request->hasFile('image_path')) {
            // Delete old image if exists
            if ($hotspot->image_path) {
                Storage::disk('public')->delete($hotspot->image_path);
            }
            $path = $request->file('image_path')
                ->store('hotspots', 'public');
            $data['image_path'] = $path;
        }

        $hotspot->update($data);

        return redirect()
            ->route('admin.hotspots.index')
            ->with('success', __('messages.hotspot_updated_successfully'));
    }

    /**
     * Delete the specified hotspot
     */
    public function destroy(Hotspot $hotspot): RedirectResponse
    {
        // Delete associated image if exists
        if ($hotspot->image_path) {
            Storage::disk('public')->delete($hotspot->image_path);
        }

        $hotspot->delete();

        return redirect()
            ->route('admin.hotspots.index')
            ->with('success', __('messages.hotspot_deleted_successfully'));
    }

    /**
     * Display hotspots for a specific scene
     */
    public function byScene(Scene $scene): View
    {
        $hotspots = $scene->hotspots()->paginate(20);
        return view('admin.hotspots.by-scene', compact('scene', 'hotspots'));
    }
}
