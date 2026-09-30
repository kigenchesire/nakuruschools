<?php

namespace App\Models;

use App\Models\Concerns\HasStatusAndOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class GalleryAlbum extends Model
{
    use HasStatusAndOrder;

    protected $fillable = ['name', 'slug', 'description', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'sort_order' => 'integer'];
    }

    protected static function booted(): void
    {
        static::saving(function (GalleryAlbum $album) {
            if (blank($album->slug) || $album->isDirty('name')) {
                $base = Str::slug($album->name) ?: 'album';
                $slug = $base;
                $i = 2;
                while (static::where('slug', $slug)->when($album->id, fn ($q) => $q->whereKeyNot($album->id))->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $album->slug = $slug;
            }
        });
    }

    public function images(): HasMany
    {
        return $this->hasMany(GalleryImage::class);
    }
}
