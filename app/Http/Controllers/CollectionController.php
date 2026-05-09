<?php

namespace App\Http\Controllers;

use App\Models\Artifact;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CollectionController extends Controller
{
    /**
     * Show the artifact collection with filter and search
     */
    public function index(Request $request): View
    {
        $query = Artifact::query()->with('category');

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->get('category'));
        }

        // Search by title or description
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title_id', 'like', "%{$search}%")
                    ->orWhere('title_en', 'like', "%{$search}%")
                    ->orWhere('description_id', 'like', "%{$search}%")
                    ->orWhere('description_en', 'like', "%{$search}%");
            });
        }

        // Pagination
        $artifacts = $query->paginate(12);

        // Get all categories for filter
        $categories = Category::withCount('artifacts')->get();

        // Get featured artifacts for sidebar
        $featured = Artifact::featured()->limit(5)->get();

        return view('collection.index', compact('artifacts', 'categories', 'featured'));
    }

    /**
     * Show a specific artifact detail page
     */
    public function show(Artifact $artifact): View
    {
        // Get related artifacts from same category
        $related = $artifact->category
            ->artifacts()
            ->where('id', '!=', $artifact->id)
            ->limit(6)
            ->get();

        return view('collection.show', compact('artifact', 'related'));
    }
}
