<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SocialLinkController extends Controller
{
    public function edit(): View
    {
        // Make sure every supported platform has a row to edit.
        foreach (array_keys(SocialLink::PLATFORMS) as $i => $platform) {
            SocialLink::firstOrCreate(['platform' => $platform], ['sort_order' => $i + 1]);
        }

        return view('admin.social.edit', ['links' => SocialLink::ordered()->get()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $platforms = array_keys(SocialLink::PLATFORMS);

        $rules = [];
        foreach ($platforms as $platform) {
            $rules["links.$platform.url"] = ['nullable', 'url:http,https', 'max:255'];
            $rules["links.$platform.is_active"] = ['nullable', 'boolean'];
            $rules["links.$platform.sort_order"] = ['nullable', 'integer', 'min:0', 'max:99'];
        }

        $input = $request->validate($rules, [], collect($platforms)->mapWithKeys(fn ($p) => [
            "links.$p.url" => SocialLink::PLATFORMS[$p][0] . ' URL',
        ])->all())['links'] ?? [];

        foreach ($platforms as $platform) {
            if (! empty($input[$platform]['is_active']) && blank($input[$platform]['url'] ?? null)) {
                return back()->withInput()->withErrors([
                    "links.$platform.url" => 'Add a URL before enabling ' . SocialLink::PLATFORMS[$platform][0] . '.',
                ]);
            }
        }

        DB::transaction(function () use ($platforms, $input) {
            foreach ($platforms as $i => $platform) {
                SocialLink::updateOrCreate(['platform' => $platform], [
                    'url' => $input[$platform]['url'] ?? null,
                    'is_active' => ! empty($input[$platform]['is_active']),
                    'sort_order' => $input[$platform]['sort_order'] ?? $i + 1,
                ]);
            }
        });

        return back()->with('success', 'Social media links updated successfully.');
    }
}
