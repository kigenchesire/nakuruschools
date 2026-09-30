<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use App\Models\Feature;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        $sections = AboutSection::active()->ordered()->get();

        return view('frontend.about', [
            'intro' => $sections->firstWhere('key', 'introduction'),
            'mission' => $sections->firstWhere('key', 'mission'),
            'vision' => $sections->firstWhere('key', 'vision'),
            'values' => $sections->firstWhere('key', 'core_values'),
            'history' => $sections->firstWhere('key', 'history'),
            'others' => $sections->whereNull('key')->values(),
            'highlights' => Feature::active()->inGroup('highlight')->ordered()->get(),
        ]);
    }
}
