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
        // Start query with category relationship
        $query = Artifact::query()->with('category');

        // Filter by category slug
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->get('category'));
            });
        }

        // Search by title or description
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title_id', 'like', "%{$search}%")
                    ->orWhere('title_en', 'like', "%{$search}%");
            });
        }

        // Pagination with query string preservation
        $artifacts = $query->paginate(12)->withQueryString();

        // Get all categories for filter pills
        $categories = Category::orderBy('name_id', 'asc')->get();

        return view('collection.index', compact('artifacts', 'categories'));
    }

    /**
     * Show a specific artifact detail page
     */
    public function show(Artifact $artifact): View
    {
        // Get related artifacts from same category (limit 4)
        $relatedArtifacts = $artifact->category
            ->artifacts()
            ->where('id', '!=', $artifact->id)
            ->limit(4)
            ->get();

        return view('collection.show', compact('artifact', 'relatedArtifacts'));
    }
}
