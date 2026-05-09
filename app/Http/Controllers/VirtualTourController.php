<?php

namespace App\Http\Controllers;

use App\Models\Scene;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class VirtualTourController extends Controller
{
    /**
     * Show the landing page with all scenes
     */
    public function index(): View
    {
        $scenes = Scene::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('tour.index', compact('scenes'));
    }

    /**
     * Show a specific scene with panorama viewer
     */
    public function show(Scene $scene): View
    {
        // Ensure scene is active
        abort_if(!$scene->is_active, 404);

        // Get related hotspots
        $hotspots = $scene->hotspots()->get();

        return view('tour.show', compact('scene', 'hotspots'));
    }

    /**
     * Get scene data as JSON for Pannellum
     * 
     * Returns hotspot data with coordinates (pitch, yaw) and metadata
     */
    public function getSceneData(Scene $scene): JsonResponse
    {
        abort_if(!$scene->is_active, 404);

        $hotspots = $scene->hotspots()
            ->select([
                'id',
                'type',
                'pitch',
                'yaw',
                'title',
                'content',
                'image_path',
                'target_scene_id',
            ])
            ->get()
            ->map(function ($hotspot) {
                $data = [
                    'id' => $hotspot->id,
                    'pitch' => $hotspot->pitch,
                    'yaw' => $hotspot->yaw,
                    'title' => $hotspot->title,
                    'type' => $hotspot->type,
                ];

                if ($hotspot->type === 'info') {
                    $data['content'] = $hotspot->content;
                    if ($hotspot->image_path) {
                        $data['image'] = asset('storage/' . $hotspot->image_path);
                    }
                } elseif ($hotspot->type === 'scene') {
                    $data['targetSceneId'] = $hotspot->target_scene_id;
                }

                return $data;
            });

        return response()->json([
            'scene' => [
                'id' => $scene->id,
                'title' => $scene->title,
                'description' => $scene->description,
                'image' => asset('storage/' . $scene->image_path),
            ],
            'hotspots' => $hotspots,
        ]);
    }
}
