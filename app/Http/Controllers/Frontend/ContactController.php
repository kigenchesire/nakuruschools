<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactEnquiryRequest;
use App\Models\ContactEnquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('frontend.contact');
    }

    public function store(ContactEnquiryRequest $request): RedirectResponse
    {
        ContactEnquiry::create($request->safe()->except('website') + [
            'status' => 'unread',
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 250, ''),
        ]);

        return redirect()->to(route('contact') . '#contact-form')
            ->with('success', 'Thank you for reaching out! Your message has been received and our team will get back to you shortly.');
    }
}
