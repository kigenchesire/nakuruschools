<?php

namespace App\Services;

use App\Models\ContactInformation;
use App\Models\Setting;
use App\Models\SocialLink;
use App\Support\Media;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Throwable;

/**
 * Site-wide data shown on every public page (settings, contact details,
 * social links). Cached forever and flushed whenever the underlying rows change.
 */
class SiteData
{
    private const CACHE_KEY = 'site-data.v1';

    /** Defaults used when a setting has never been saved (or the DB is unavailable). */
    public const DEFAULT_SETTINGS = [
        'tagline' => 'Inspiring Excellence. Building Futures.',
        'logo' => null,
        'favicon' => null,
        'footer_about' => 'Nakuru Schools nurtures confident, disciplined and creative learners in a safe, caring and well-resourced environment.',
        'footer_text' => null,
        'powered_by' => null,
        'meta_description' => 'Nakuru Schools — a caring learning community in Nakuru, Kenya, nurturing confident, disciplined and creative learners.',
        'meta_keywords' => 'Nakuru Schools, school in Nakuru, Kenya school, CBC school, admissions Nakuru',
        'og_image' => null,
        'enquire_button_text' => 'Enquire Now',
        'home_welcome_eyebrow' => 'Welcome to Nakuru Schools',
        'home_welcome_title' => 'A place where every learner can thrive',
        'home_welcome_text' => "At Nakuru Schools, we are committed to nurturing learners who are confident, disciplined, creative and prepared to make a positive contribution to society.\n\nOur dedicated teachers, supportive community and well-rounded programme give every child the foundation to discover their strengths and pursue excellence.",
        'cta_title' => 'Ready to join our school?',
        'cta_text' => 'Discover a learning environment designed to help every learner thrive. Our admissions team will guide you through every step.',
        'cta_button_text' => 'Contact Admissions',
        'cta_button_url' => '/contact',
    ];

    /** Per-request copy, so the cache store is read at most once per request. */
    private ?array $memo = null;

    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);

        if (app()->resolved(self::class)) {
            app(self::class)->memo = null;
        }
    }

    /** @return array{settings: array, contact: array, social: array} */
    public function all(): array
    {
        if ($this->memo !== null) {
            return $this->memo;
        }

        try {
            $data = Cache::rememberForever(self::CACHE_KEY, fn () => $this->load());
        } catch (Throwable $e) {
            report($e);
            $data = $this->fallback();
        }

        return $this->memo = $data;
    }

    public function setting(string $key, mixed $default = null): mixed
    {
        return $this->all()['settings'][$key] ?? $default ?? (self::DEFAULT_SETTINGS[$key] ?? null);
    }

    public function settings(): array
    {
        return $this->all()['settings'];
    }

    public function contact(): object
    {
        return (object) $this->all()['contact'];
    }

    public function socialLinks(): Collection
    {
        return collect($this->all()['social'])->map(fn ($link) => (object) $link);
    }

    public function schoolName(): string
    {
        return $this->contact()->school_name ?: config('app.name');
    }

    public function logoUrl(): ?string
    {
        return Media::url($this->setting('logo'));
    }

    public function faviconUrl(): ?string
    {
        return Media::url($this->setting('favicon'));
    }

    private function load(): array
    {
        $stored = Setting::query()->pluck('value', 'key')->all();
        $settings = array_merge(self::DEFAULT_SETTINGS, array_filter($stored, fn ($v) => $v !== null && $v !== ''));

        $contact = ContactInformation::current();

        $social = SocialLink::query()->active()->whereNotNull('url')->where('url', '!=', '')->ordered()->get()
            ->map(fn (SocialLink $link) => [
                'platform' => $link->platform,
                'label' => $link->label,
                'icon' => $link->icon,
                'url' => $link->url,
            ])->all();

        return [
            'settings' => $settings,
            'contact' => $contact->only([
                'school_name', 'phone', 'alt_phone', 'email', 'alt_email', 'physical_address',
                'postal_address', 'office_hours', 'latitude', 'longitude',
            ]) + [
                'map_embed_url' => $contact->map_embed_url,
                'directions_url' => $contact->directions_url,
            ],
            'social' => $social,
        ];
    }

    private function fallback(): array
    {
        return [
            'settings' => self::DEFAULT_SETTINGS,
            'contact' => [
                'school_name' => config('app.name'), 'phone' => null, 'alt_phone' => null, 'email' => null,
                'alt_email' => null, 'physical_address' => null, 'postal_address' => null, 'office_hours' => null,
                'latitude' => null, 'longitude' => null, 'map_embed_url' => null, 'directions_url' => null,
            ],
            'social' => [],
        ];
    }
}
