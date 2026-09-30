<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\HasStatusAndOrder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use FlushesSiteCache, HasStatusAndOrder;

    /** Supported platforms: key => [label, Bootstrap Icon]. */
    public const PLATFORMS = [
        'facebook' => ['Facebook', 'bi-facebook'],
        'instagram' => ['Instagram', 'bi-instagram'],
        'x' => ['X (Twitter)', 'bi-twitter-x'],
        'youtube' => ['YouTube', 'bi-youtube'],
        'tiktok' => ['TikTok', 'bi-tiktok'],
        'linkedin' => ['LinkedIn', 'bi-linkedin'],
        'whatsapp' => ['WhatsApp', 'bi-whatsapp'],
    ];

    protected $fillable = ['platform', 'url', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'sort_order' => 'integer'];
    }

    protected function label(): Attribute
    {
        return Attribute::get(fn () => self::PLATFORMS[$this->platform][0] ?? ucfirst($this->platform));
    }

    protected function icon(): Attribute
    {
        return Attribute::get(fn () => self::PLATFORMS[$this->platform][1] ?? 'bi-link-45deg');
    }
}
