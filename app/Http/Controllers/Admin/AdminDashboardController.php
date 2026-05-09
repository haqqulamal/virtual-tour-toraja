<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scene;
use App\Models\Hotspot;
use App\Models\Artifact;
use App\Models\Category;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $scenesCount = Scene::count();
        $hotspotsCount = Hotspot::count();
        $artifactsCount = Artifact::count();
        $categoriesCount = Category::count();

        $recentScenes = Scene::latest()->limit(5)->get();
        $recentArtifacts = Artifact::latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'scenesCount',
            'hotspotsCount',
            'artifactsCount',
            'categoriesCount',
            'recentScenes',
            'recentArtifacts',
        ));
    }
}
