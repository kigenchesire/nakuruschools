<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class Resource extends Model
{
    use SoftDeletes;

    /** Private disk: files are only reachable through the download route. */
    public const DISK = 'local';

    public const CATEGORIES = [
        'Admissions', 'Brochures', 'Fee Structures', 'Calendars', 'Policies', 'Forms', 'Other',
    ];

    public const ALLOWED_EXTENSIONS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'zip'];

    protected $fillable = [
        'title', 'slug', 'description', 'file', 'original_name', 'file_type',
        'file_size', 'category', 'is_active', 'created_by',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'file_size' => 'integer', 'download_count' => 'integer'];
    }

    protected static function booted(): void
    {
        static::saving(function (Resource $resource) {
            if (blank($resource->slug)) {
                $base = Str::slug($resource->title) ?: 'resource';
                $slug = $base;
                $i = 2;
                while (static::withTrashed()->where('slug', $slug)->when($resource->id, fn ($q) => $q->whereKeyNot($resource->id))->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $resource->slug = $slug;
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function humanSize(): Attribute
    {
        return Attribute::get(fn () => Number::fileSize($this->file_size, precision: 1));
    }

    protected function icon(): Attribute
    {
        return Attribute::get(fn () => match ($this->file_type) {
            'pdf' => 'bi-file-earmark-pdf',
            'doc', 'docx' => 'bi-file-earmark-word',
            'xls', 'xlsx' => 'bi-file-earmark-excel',
            'ppt', 'pptx' => 'bi-file-earmark-slides',
            'jpg', 'jpeg', 'png' => 'bi-file-earmark-image',
            'zip' => 'bi-file-earmark-zip',
            default => 'bi-file-earmark-text',
        });
    }

    /** Name offered to the browser: the title, never the stored random filename. */
    public function downloadName(): string
    {
        return Str::slug($this->title) . '.' . $this->file_type;
    }
}
