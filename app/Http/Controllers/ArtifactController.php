<?php

namespace App\Http\Controllers;

use App\Models\Artifact;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArtifactController extends Controller
{
    public function index(Request $request): View
    {
        $query = Artifact::where('is_published', true);

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Search by keyword
        if ($request->has('search') && $request->search) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm)
                  ->orWhere('keywords', 'like', $searchTerm);
            });
        }

        $artifacts = $query->paginate(12);
        $categories = Category::withCount('artifacts')->get();

        return view('frontend.artifacts.index', compact('artifacts', 'categories'));
    }

    public function show(Artifact $artifact): View
    {
        abort_if(!$artifact->is_published, 404);

        $relatedArtifacts = Artifact::where('category_id', $artifact->category_id)
            ->where('id', '!=', $artifact->id)
            ->where('is_published', true)
            ->limit(4)
            ->get();

        return view('frontend.artifacts.show', compact('artifact', 'relatedArtifacts'));
    }

    public function search(Request $request)
    {
        $query = Artifact::where('is_published', true);

        if ($request->has('q') && $request->q) {
            $searchTerm = '%' . $request->q . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm)
                  ->orWhere('keywords', 'like', $searchTerm)
                  ->orWhere('cultural_significance', 'like', $searchTerm);
            });
        }

        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $artifacts = $query->limit(20)->get();

        return response()->json($artifacts);
    }
}
