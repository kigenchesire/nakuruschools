<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesListItems;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqRequest;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FaqController extends Controller
{
    use ManagesListItems;

    protected function listModel(): string
    {
        return Faq::class;
    }

    public function index(): View
    {
        return view('admin.faqs.index', ['faqs' => Faq::ordered()->get()]);
    }

    public function create(): View
    {
        return view('admin.faqs.form', ['faq' => new Faq(['is_active' => true, 'sort_order' => Faq::nextSortOrder()])]);
    }

    public function store(FaqRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] ??= Faq::nextSortOrder();
        Faq::create($data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faqs.form', compact('faq'));
    }

    public function update(FaqRequest $request, Faq $faq): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] ??= $faq->sort_order;
        $faq->update($data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted.');
    }

    public function toggle(Faq $faq): RedirectResponse
    {
        return $this->toggleActive($faq, 'FAQ');
    }
}
