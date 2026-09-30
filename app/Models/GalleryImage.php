<?php

namespace App\Models;

use App\Models\Concerns\HasStatusAndOrder;
use App\Support\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryImage extends Model
{
    use HasStatusAndOrder;

    protected $fillable = [
        'gallery_album_id', 'image', 'thumbnail', 'caption', 'is_featured', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_featured' => 'boolean', 'is_active' => 'boolean', 'sort_order' => 'integer'];
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => Media::url($this->image));
    }

    protected function thumbnailUrl(): Attribute
    {
        return Attribute::get(fn () => Media::url($this->thumbnail ?: $this->image));
    }

    protected function altText(): Attribute
    {
        return Attribute::get(fn () => $this->caption ?: ($this->album?->name ? $this->album->name . ' photo' : 'School photo'));
    }
}
