<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('category');
        $search = trim((string) $request->query('q'));

        $news = News::latestPublished()
            ->with('author')
            ->when($category, fn ($q) => $q->where('category', $category))
            ->when($search !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%")))
            ->paginate(9)
            ->withQueryString();

        // Show the featured story as a hero only on the unfiltered first page.
        $featured = null;
        if (! $category && $search === '' && $news->currentPage() === 1) {
            $featured = News::latestPublished()->with('author')->where('is_featured', true)->first();
        }

        return view('frontend.news.index', [
            'news' => $news,
            'featured' => $featured,
            'category' => $category,
            'search' => $search,
            'categories' => News::published()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category'),
        ]);
    }

    public function show(News $news): View
    {
        abort_unless($news->isPublished() && $news->published_at?->isPast(), 404);

        $news->load('author');
        News::whereKey($news->id)->increment('views');

        $related = News::latestPublished()
            ->with('author')
            ->whereKeyNot($news->id)
            ->when($news->category, fn ($q) => $q->orderByRaw('category = ? desc', [$news->category]))
            ->take(3)
            ->get();

        return view('frontend.news.show', compact('news', 'related'));
    }
}
