<?php

namespace App\Models\Concerns;

use App\Services\SiteData;

/**
 * Models whose data is shown on every public page (settings, contact details,
 * social links) are cached by SiteData; any write clears that cache.
 */
trait FlushesSiteCache
{
    public static function bootFlushesSiteCache(): void
    {
        static::saved(fn () => SiteData::flush());
        static::deleted(fn () => SiteData::flush());
    }
}
