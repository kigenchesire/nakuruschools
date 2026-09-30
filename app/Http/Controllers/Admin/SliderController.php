<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesListItems;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SliderRequest;
use App\Models\Slider;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SliderController extends Controller
{
    use ManagesListItems;

    public function __construct(private ImageService $images)
    {
    }

    protected function listModel(): string
    {
        return Slider::class;
    }

    public function index(): View
    {
        return view('admin.sliders.index', ['sliders' => Slider::ordered()->get()]);
    }

    public function create(): View
    {
        return view('admin.sliders.form', ['slider' => new Slider(['is_active' => true, 'sort_order' => Slider::nextSortOrder()])]);
    }

    public function store(SliderRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $data['sort_order'] ??= Slider::nextSortOrder();
        $data['image'] = $this->images->store($request->file('image'), 'sliders', 1920)['path'];

        Slider::create($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slide created successfully.');
    }

    public function edit(Slider $slider): View
    {
        return view('admin.sliders.form', compact('slider'));
    }

    public function update(SliderRequest $request, Slider $slider): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $data['sort_order'] ??= $slider->sort_order;

        if ($request->hasFile('image')) {
            $old = $slider->image;
            $data['image'] = $this->images->store($request->file('image'), 'sliders', 1920)['path'];
            $this->images->delete($old);
        }

        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slide updated successfully.');
    }

    public function destroy(Slider $slider): RedirectResponse
    {
        $this->images->delete($slider->image);
        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('success', 'Slide deleted.');
    }

    public function toggle(Slider $slider): RedirectResponse
    {
        return $this->toggleActive($slider, 'Slide');
    }
}
