<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ResourceRequest;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResourceController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));
        $category = $request->query('category');

        $resources = Resource::with('creator')
            ->when($search !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")))
            ->when($category, fn ($q) => $q->where('category', $category))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.resources.index', [
            'resources' => $resources,
            'search' => $search,
            'category' => $category,
            'categories' => $this->categories(),
        ]);
    }

    public function create(): View
    {
        return view('admin.resources.form', ['resource' => new Resource(['is_active' => true]), 'categories' => $this->categories()]);
    }

    public function store(ResourceRequest $request): RedirectResponse
    {
        $resource = new Resource($request->safe()->except('file'));
        $resource->fill($this->storeFile($request->file('file')));
        $resource->created_by = $request->user()->id;
        $resource->save();

        return redirect()->route('admin.resources.index')->with('success', 'Resource uploaded successfully.');
    }

    public function edit(Resource $resource): View
    {
        return view('admin.resources.form', ['resource' => $resource, 'categories' => $this->categories()]);
    }

    public function update(ResourceRequest $request, Resource $resource): RedirectResponse
    {
        $resource->fill($request->safe()->except('file'));

        if ($request->hasFile('file')) {
            $old = $resource->file;
            $resource->fill($this->storeFile($request->file('file')));
            Storage::disk(Resource::DISK)->delete($old);
        }

        $resource->save();

        return redirect()->route('admin.resources.index')->with('success', 'Resource updated successfully.');
    }

    public function destroy(Resource $resource): RedirectResponse
    {
        Storage::disk(Resource::DISK)->delete($resource->file);
        $resource->forceDelete();

        return redirect()->route('admin.resources.index')->with('success', 'Resource deleted.');
    }

    public function toggle(Resource $resource): RedirectResponse
    {
        $resource->update(['is_active' => ! $resource->is_active]);

        return back()->with('success', $resource->is_active ? 'Resource is now visible on the website.' : 'Resource hidden from the website.');
    }

    /** Lets staff check a file, including hidden ones, without counting a download. */
    public function download(Resource $resource): StreamedResponse
    {
        abort_unless(Storage::disk(Resource::DISK)->exists($resource->file), 404);

        return Storage::disk(Resource::DISK)->download($resource->file, $resource->downloadName());
    }

    /**
     * Stores the upload under a random name; the client filename is only kept
     * as metadata and never used for the path.
     */
    private function storeFile(UploadedFile $file): array
    {
        $extension = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension());
        if (! in_array($extension, Resource::ALLOWED_EXTENSIONS, true)) {
            $extension = strtolower($file->getClientOriginalExtension());
        }

        return [
            'file' => $file->storeAs('resources', Str::random(40) . '.' . $extension, Resource::DISK),
            'original_name' => Str::limit($file->getClientOriginalName(), 250, ''),
            'file_type' => $extension,
            'file_size' => $file->getSize(),
        ];
    }

    private function categories(): array
    {
        return Resource::query()->whereNotNull('category')->distinct()->pluck('category')
            ->merge(Resource::CATEGORIES)->unique()->sort()->values()->all();
    }
}
