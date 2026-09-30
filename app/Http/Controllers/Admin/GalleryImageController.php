<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesListItems;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GalleryImageRequest;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GalleryImageController extends Controller
{
    use ManagesListItems;

    public function __construct(private ImageService $images)
    {
    }

    protected function listModel(): string
    {
        return GalleryImage::class;
    }

    public function index(Request $request): View
    {
        $albumFilter = $request->query('album');

        $images = GalleryImage::with('album')
            ->when($albumFilter === 'none', fn ($q) => $q->whereNull('gallery_album_id'))
            ->when(is_numeric($albumFilter), fn ($q) => $q->where('gallery_album_id', $albumFilter))
            ->when($request->query('status') === 'inactive', fn ($q) => $q->where('is_active', false))
            ->when($request->query('status') === 'featured', fn ($q) => $q->where('is_featured', true))
            ->ordered()
            ->paginate(24)
            ->withQueryString();

        return view('admin.gallery.index', [
            'images' => $images,
            'albums' => GalleryAlbum::ordered()->get(['id', 'name']),
            'albumFilter' => $albumFilter,
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.gallery.create', [
            'albums' => GalleryAlbum::ordered()->get(['id', 'name']),
            'selectedAlbum' => $request->integer('album') ?: null,
        ]);
    }

    public function store(GalleryImageRequest $request): RedirectResponse
    {
        $order = GalleryImage::nextSortOrder();
        $stored = [];

        try {
            DB::transaction(function () use ($request, &$order, &$stored) {
                foreach ($request->file('images') as $file) {
                    $result = $this->images->store($file, 'gallery', 1600, 600);
                    $stored[] = $result;

                    GalleryImage::create([
                        'gallery_album_id' => $request->validated('gallery_album_id'),
                        'image' => $result['path'],
                        'thumbnail' => $result['thumbnail'],
                        'caption' => $request->validated('caption'),
                        'is_featured' => $request->boolean('is_featured'),
                        'is_active' => $request->boolean('is_active'),
                        'sort_order' => $order++,
                    ]);
                }
            });
        } catch (\Throwable $e) {
            // Don't leave orphaned files behind if a database write failed.
            foreach ($stored as $file) {
                $this->images->delete($file['path'], $file['thumbnail']);
            }
            throw $e;
        }

        $count = count($stored);

        return redirect()->route('admin.gallery.index')
            ->with('success', $count === 1 ? 'Image uploaded successfully.' : "{$count} images uploaded successfully.");
    }

    public function edit(GalleryImage $image): View
    {
        return view('admin.gallery.edit', [
            'image' => $image,
            'albums' => GalleryAlbum::ordered()->get(['id', 'name']),
        ]);
    }

    public function update(GalleryImageRequest $request, GalleryImage $image): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $data['sort_order'] ??= $image->sort_order;

        if ($request->hasFile('image')) {
            $result = $this->images->store($request->file('image'), 'gallery', 1600, 600);
            $this->images->delete($image->image, $image->thumbnail);
            $data['image'] = $result['path'];
            $data['thumbnail'] = $result['thumbnail'];
        }

        $image->update($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Image updated successfully.');
    }

    public function destroy(GalleryImage $image): RedirectResponse
    {
        $this->images->delete($image->image, $image->thumbnail);
        $image->delete();

        return back()->with('success', 'Image deleted.');
    }

    public function toggle(GalleryImage $image): RedirectResponse
    {
        return $this->toggleActive($image, 'Image');
    }
}
