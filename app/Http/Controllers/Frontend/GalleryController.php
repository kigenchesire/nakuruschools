<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $albums = GalleryAlbum::active()->ordered()
            ->whereHas('images', fn ($q) => $q->where('is_active', true))
            ->withCount(['images' => fn ($q) => $q->where('is_active', true)])
            ->get();

        $current = $albums->firstWhere('slug', $request->query('album'));

        $images = GalleryImage::active()
            ->with('album')
            // Hide photos whose album has been switched off.
            ->where(fn (Builder $q) => $q->whereNull('gallery_album_id')
                ->orWhereHas('album', fn ($a) => $a->where('is_active', true)))
            ->when($current, fn ($q) => $q->where('gallery_album_id', $current->id))
            ->ordered()
            ->paginate(24)
            ->withQueryString();

        return view('frontend.gallery', compact('albums', 'current', 'images'));
    }
}
