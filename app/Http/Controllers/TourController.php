<?php

namespace App\Http\Controllers;

use App\Models\Scene;
use App\Models\Hotspot;
use Illuminate\View\View;

class TourController extends Controller
{
    public function index(): View
    {
        $scenes = Scene::where('is_published', true)
            ->orderBy('order')
            ->paginate(12);

        return view('frontend.index', compact('scenes'));
    }

    public function show(Scene $scene): View
    {
        abort_if(!$scene->is_published, 404);

        $scenes = Scene::where('is_published', true)
            ->orderBy('order')
            ->get();

        return view('frontend.tour', [
            'scene' => $scene,
            'scenes' => $scenes,
        ]);
    }

    public function getSceneData(Scene $scene)
    {
        abort_if(!$scene->is_published, 404);

        $hotspots = $scene->hotspots()->get()->map(function ($hotspot) {
            return [
                'pitch' => $hotspot->pitch,
                'yaw' => $hotspot->yaw,
                'type' => 'info',
                'text' => $hotspot->title,
                'description' => $hotspot->description,
                'hotspotType' => $hotspot->type,
                'linkedSceneId' => $hotspot->linked_scene_id,
                'artifactId' => $hotspot->artifact_id,
            ];
        });

        return response()->json([
            'title' => $scene->title,
            'description' => $scene->description,
            'image' => asset('uploads/scenes/' . $scene->panorama_image),
            'hotspots' => $hotspots,
        ]);
    }
}
