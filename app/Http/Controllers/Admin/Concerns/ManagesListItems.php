<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Drag-and-drop reordering and the active/inactive switch shared by the
 * sortable admin lists (sliders, features, FAQs, albums, gallery images...).
 */
trait ManagesListItems
{
    /** @return class-string<Model> */
    abstract protected function listModel(): string;

    public function reorder(Request $request): JsonResponse
    {
        $ids = $request->validate([
            'ids' => ['required', 'array', 'max:500'],
            'ids.*' => ['integer'],
        ])['ids'];

        $model = $this->listModel();

        DB::transaction(function () use ($ids, $model) {
            foreach (array_values($ids) as $position => $id) {
                $model::whereKey($id)->update(['sort_order' => $position + 1]);
            }
        });

        return response()->json(['message' => 'Order saved.']);
    }

    protected function toggleActive(Model $item, string $label): RedirectResponse
    {
        $item->update(['is_active' => ! $item->is_active]);

        return back()->with('success', $label . ($item->is_active ? ' activated.' : ' deactivated.'));
    }
}
