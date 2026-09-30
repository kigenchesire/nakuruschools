<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesListItems;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GalleryAlbumRequest;
use App\Models\GalleryAlbum;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GalleryAlbumController extends Controller
{
    use ManagesListItems;

    protected function listModel(): string
    {
        return GalleryAlbum::class;
    }

    public function index(): View
    {
        return view('admin.gallery.albums.index', [
            'albums' => GalleryAlbum::withCount('images')->ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.gallery.albums.form', ['album' => new GalleryAlbum(['is_active' => true, 'sort_order' => GalleryAlbum::nextSortOrder()])]);
    }

    public function store(GalleryAlbumRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] ??= GalleryAlbum::nextSortOrder();
        GalleryAlbum::create($data);

        return redirect()->route('admin.gallery.albums.index')->with('success', 'Album created successfully.');
    }

    public function edit(GalleryAlbum $album): View
    {
        return view('admin.gallery.albums.form', compact('album'));
    }

    public function update(GalleryAlbumRequest $request, GalleryAlbum $album): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] ??= $album->sort_order;
        $album->update($data);

        return redirect()->route('admin.gallery.albums.index')->with('success', 'Album updated successfully.');
    }

    public function destroy(GalleryAlbum $album): RedirectResponse
    {
        // Images are kept and become "uncategorised" (FK is nullOnDelete).
        $album->delete();

        return redirect()->route('admin.gallery.albums.index')->with('success', 'Album deleted. Its images were kept as uncategorised.');
    }
}
