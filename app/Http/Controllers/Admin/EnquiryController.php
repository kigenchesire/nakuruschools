<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactEnquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EnquiryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));
        $status = $request->query('status');

        $enquiries = ContactEnquiry::query()
            ->when($search !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('subject', 'like', "%{$search}%")))
            ->when(array_key_exists((string) $status, ContactEnquiry::STATUSES), fn ($q) => $q->where('status', $status))
            // Archived messages are hidden unless explicitly filtered for.
            ->when(! $status, fn ($q) => $q->where('status', '!=', 'archived'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $counts = ContactEnquiry::query()->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        return view('admin.enquiries.index', compact('enquiries', 'search', 'status', 'counts'));
    }

    public function show(ContactEnquiry $enquiry): View
    {
        $enquiry->markAsRead();

        return view('admin.enquiries.show', compact('enquiry'));
    }

    public function updateStatus(Request $request, ContactEnquiry $enquiry): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', Rule::in(array_keys(ContactEnquiry::STATUSES))]]);

        $enquiry->update($data);

        return back()->with('success', 'Enquiry marked as ' . strtolower($enquiry->status_label) . '.');
    }

    public function destroy(ContactEnquiry $enquiry): RedirectResponse
    {
        $enquiry->delete();

        return redirect()->route('admin.enquiries.index')->with('success', 'Enquiry deleted.');
    }
}
