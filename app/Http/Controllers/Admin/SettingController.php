<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Models\Setting;
use App\Services\ImageService;
use App\Services\SiteData;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(private ImageService $images)
    {
    }

    public function edit(SiteData $site): View
    {
        return view('admin.settings.edit', ['settings' => $site->settings()]);
    }

    public function update(SettingRequest $request): RedirectResponse
    {
        $stored = Setting::query()->pluck('value', 'key');

        foreach (SettingRequest::TEXT_KEYS as $key) {
            Setting::put($key, $request->validated($key));
        }

        // Logo and favicon are kept byte-for-byte; the share image is resized.
        foreach (['logo' => 'branding', 'favicon' => 'branding', 'og_image' => 'branding'] as $key => $dir) {
            if ($request->hasFile($key)) {
                $path = $key === 'og_image'
                    ? $this->images->store($request->file($key), $dir, 1200)['path']
                    : $this->images->storeOriginal($request->file($key), $dir);
                $this->images->delete($stored[$key] ?? null);
                Setting::put($key, $path);
            } elseif ($request->boolean("remove_$key")) {
                $this->images->delete($stored[$key] ?? null);
                Setting::put($key, null);
            }
        }

        SiteData::flush();

        return back()->with('success', 'Settings saved successfully.');
    }
}
