<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResourceController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('category');

        $resources = Resource::active()
            ->when($category, fn ($q) => $q->where('category', $category))
            ->orderBy('category')->orderBy('title')
            ->paginate(18)
            ->withQueryString();

        return view('frontend.resources', [
            'resources' => $resources,
            'category' => $category,
            'categories' => Resource::active()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category'),
        ]);
    }

    /** Streams the file from private storage; the storage path is never exposed. */
    public function download(Resource $resource): StreamedResponse
    {
        abort_unless($resource->is_active, 404);
        abort_unless(Storage::disk(Resource::DISK)->exists($resource->file), 404);

        Resource::whereKey($resource->id)->increment('download_count');

        return Storage::disk(Resource::DISK)->download($resource->file, $resource->downloadName());
    }
}
