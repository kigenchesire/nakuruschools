<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactInformationRequest;
use App\Models\ContactInformation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactInformationController extends Controller
{
    public function edit(): View
    {
        return view('admin.contact.edit', ['contact' => ContactInformation::current()]);
    }

    public function update(ContactInformationRequest $request): RedirectResponse
    {
        ContactInformation::current()->update($request->validated());

        return back()->with('success', 'Contact information updated successfully.');
    }
}
