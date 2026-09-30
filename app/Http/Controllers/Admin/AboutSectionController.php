<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AboutSectionRequest;
use App\Models\AboutSection;
use App\Services\HtmlSanitizer;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AboutSectionController extends Controller
{
    public function __construct(private ImageService $images, private HtmlSanitizer $sanitizer)
    {
    }

    public function index(): View
    {
        return view('admin.about.index', ['sections' => AboutSection::ordered()->get()]);
    }

    /** Creates an additional "other information" section shown on the About page. */
    public function create(): View
    {
        return view('admin.about.form', ['section' => new AboutSection(['is_active' => true, 'sort_order' => AboutSection::nextSortOrder()])]);
    }

    public function store(AboutSectionRequest $request): RedirectResponse
    {
        $section = new AboutSection($this->payload($request));
        $section->sort_order ??= AboutSection::nextSortOrder();
        $section->save();

        return redirect()->route('admin.about.index')->with('success', 'Section created successfully.');
    }

    public function edit(AboutSection $section): View
    {
        return view('admin.about.form', compact('section'));
    }

    public function update(AboutSectionRequest $request, AboutSection $section): RedirectResponse
    {
        $section->update($this->payload($request, $section));

        return redirect()->route('admin.about.index')->with('success', "\"{$section->title}\" updated successfully.");
    }

    public function destroy(AboutSection $section): RedirectResponse
    {
        if ($section->isFixed()) {
            return back()->with('error', 'Core sections cannot be deleted. Deactivate it instead to hide it.');
        }

        $this->images->delete($section->image);
        $section->delete();

        return redirect()->route('admin.about.index')->with('success', 'Section deleted.');
    }

    private function payload(AboutSectionRequest $request, ?AboutSection $section = null): array
    {
        $data = $request->safe()->except(['image', 'remove_image']);
        $data['content'] = $this->sanitizer->clean($data['content'] ?? null);
        $data['sort_order'] ??= $section?->sort_order;

        if ($request->hasFile('image')) {
            $this->images->delete($section?->image);
            $data['image'] = $this->images->store($request->file('image'), 'about', 1600)['path'];
        } elseif ($request->boolean('remove_image') && $section) {
            $this->images->delete($section->image);
            $data['image'] = null;
        }

        return $data;
    }
}
