<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $pages = collect(['home', 'about', 'news.index', 'gallery', 'resources.index', 'faqs', 'contact'])
            ->map(fn ($name) => ['loc' => route($name), 'lastmod' => null]);

        $articles = News::published()->latest('updated_at')->get(['slug', 'updated_at'])
            ->map(fn ($n) => ['loc' => route('news.show', $n->slug), 'lastmod' => $n->updated_at->toAtomString()]);

        return response()
            ->view('frontend.sitemap', ['urls' => $pages->concat($articles)])
            ->header('Content-Type', 'application/xml');
    }
}
