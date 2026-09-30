<?php

namespace App\Models;

use App\Models\Concerns\HasStatusAndOrder;
use App\Support\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    use HasStatusAndOrder;

    /** Sections the site layout depends on; these cannot be deleted. */
    public const FIXED_KEYS = [
        'introduction' => 'Introduction',
        'mission' => 'Mission',
        'vision' => 'Vision',
        'core_values' => 'Core Values',
        'history' => 'History',
    ];

    protected $fillable = [
        'key', 'title', 'subtitle', 'summary', 'content',
        'image', 'icon', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'sort_order' => 'integer'];
    }

    public function isFixed(): bool
    {
        return $this->key !== null && array_key_exists($this->key, self::FIXED_KEYS);
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => Media::url($this->image));
    }

    /**
     * Core values are entered one per line in the summary field.
     */
    protected function valueList(): Attribute
    {
        return Attribute::get(fn () => collect(preg_split('/\r\n|\r|\n/', (string) $this->summary))
            ->map(fn ($line) => trim($line, " \t-•"))
            ->filter()
            ->values());
    }
}
