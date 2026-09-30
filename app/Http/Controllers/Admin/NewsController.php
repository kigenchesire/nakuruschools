<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsRequest;
use App\Models\News;
use App\Services\HtmlSanitizer;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function __construct(private ImageService $images, private HtmlSanitizer $sanitizer)
    {
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));
        $status = $request->query('status');

        $sortable = ['title', 'published_at', 'created_at'];
        $sort = in_array($request->query('sort'), $sortable, true) ? $request->query('sort') : 'created_at';
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';

        $news = News::with('author')
            ->when($search !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('title', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%")))
            ->when(in_array($status, [News::STATUS_DRAFT, News::STATUS_PUBLISHED], true), fn ($q) => $q->where('status', $status))
            ->when($status === 'featured', fn ($q) => $q->where('is_featured', true))
            ->orderBy($sort, $direction)
            ->paginate(15)
            ->withQueryString();

        return view('admin.news.index', compact('news', 'search', 'status', 'sort', 'direction'));
    }

    public function create(): View
    {
        return view('admin.news.form', [
            'news' => new News(['status' => News::STATUS_DRAFT, 'published_at' => now()]),
            'categories' => $this->categories(),
        ]);
    }

    public function store(NewsRequest $request): RedirectResponse
    {
        $news = new News($this->payload($request));
        $news->author_id = $request->user()->id;
        $news->save();

        return redirect()->route('admin.news.index')->with('success', 'Article created successfully.');
    }

    public function show(News $news): View
    {
        $news->load('author');

        return view('admin.news.show', compact('news'));
    }

    public function edit(News $news): View
    {
        return view('admin.news.form', ['news' => $news, 'categories' => $this->categories()]);
    }

    public function update(NewsRequest $request, News $news): RedirectResponse
    {
        $news->update($this->payload($request, $news));

        return redirect()->route('admin.news.index')->with('success', 'Article updated successfully.');
    }

    public function destroy(News $news): RedirectResponse
    {
        // Soft delete: the image is kept so the article could be restored.
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Article deleted.');
    }

    public function togglePublish(News $news): RedirectResponse
    {
        $publishing = ! $news->isPublished();

        $news->status = $publishing ? News::STATUS_PUBLISHED : News::STATUS_DRAFT;
        if ($publishing && ! $news->published_at) {
            $news->published_at = now();
        }
        $news->save();

        return back()->with('success', $publishing ? 'Article published successfully.' : 'Article moved back to drafts.');
    }

    public function toggleFeatured(News $news): RedirectResponse
    {
        $news->update(['is_featured' => ! $news->is_featured]);

        return back()->with('success', $news->is_featured ? 'Article marked as featured.' : 'Article removed from featured.');
    }

    private function payload(NewsRequest $request, ?News $news = null): array
    {
        $data = $request->safe()->except(['image', 'remove_image']);
        $data['content'] = $this->sanitizer->clean($data['content']);

        if ($data['status'] === News::STATUS_PUBLISHED && empty($data['published_at'])) {
            $data['published_at'] = $news?->published_at ?? now();
        }

        if ($request->hasFile('image')) {
            $this->images->delete($news?->image);
            $data['image'] = $this->images->store($request->file('image'), 'news', 1600)['path'];
        } elseif ($request->boolean('remove_image') && $news) {
            $this->images->delete($news->image);
            $data['image'] = null;
        }

        return $data;
    }

    private function categories(): array
    {
        return News::query()->whereNotNull('category')->distinct()->pluck('category')
            ->merge(News::CATEGORIES)->unique()->sort()->values()->all();
    }
}
