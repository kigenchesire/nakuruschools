<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\GalleryImage;
use App\Models\News;
use App\Models\Resource;
use App\Models\Slider;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $features = Feature::active()->ordered()->get()->groupBy('group');

        $about = AboutSection::active()
            ->whereIn('key', ['introduction', 'mission', 'vision', 'core_values'])
            ->get()
            ->keyBy('key');

        // Featured photos first, topped up with the most recent uploads.
        $gallery = GalleryImage::active()->with('album')
            ->orderByDesc('is_featured')->orderByDesc('created_at')
            ->take(6)->get();

        return view('frontend.home', [
            'sliders' => Slider::active()->ordered()->get(),
            'whyChooseUs' => $features->get('why_choose_us', collect()),
            'highlights' => $features->get('highlight', collect()),
            'about' => $about,
            'news' => News::latestPublished()->with('author')->take(3)->get(),
            'gallery' => $gallery,
            'faqs' => Faq::active()->ordered()->take(5)->get(),
            'resources' => Resource::active()->latest()->take(2)->get(),
        ]);
    }
}
