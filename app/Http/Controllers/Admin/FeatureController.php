<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesListItems;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FeatureRequest;
use App\Models\Feature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeatureController extends Controller
{
    use ManagesListItems;

    protected function listModel(): string
    {
        return Feature::class;
    }

    public function index(): View
    {
        $features = Feature::ordered()->get()->groupBy('group');

        return view('admin.features.index', ['groups' => Feature::GROUPS, 'features' => $features]);
    }

    public function create(Request $request): View
    {
        $group = array_key_exists($request->query('group'), Feature::GROUPS) ? $request->query('group') : 'why_choose_us';

        return view('admin.features.form', [
            'feature' => new Feature(['group' => $group, 'is_active' => true, 'sort_order' => Feature::nextSortOrder()]),
        ]);
    }

    public function store(FeatureRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] ??= Feature::nextSortOrder();
        Feature::create($data);

        return redirect()->route('admin.features.index')->with('success', 'Item created successfully.');
    }

    public function edit(Feature $feature): View
    {
        return view('admin.features.form', compact('feature'));
    }

    public function update(FeatureRequest $request, Feature $feature): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] ??= $feature->sort_order;
        $feature->update($data);

        return redirect()->route('admin.features.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(Feature $feature): RedirectResponse
    {
        $feature->delete();

        return redirect()->route('admin.features.index')->with('success', 'Item deleted.');
    }

    public function toggle(Feature $feature): RedirectResponse
    {
        return $this->toggleActive($feature, 'Item');
    }
}
