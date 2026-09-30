<?php

namespace App\Models;

use App\Models\Concerns\HasStatusAndOrder;
use App\Support\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasStatusAndOrder;

    protected $fillable = [
        'title', 'subtitle', 'description', 'image',
        'button_text', 'button_url', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'sort_order' => 'integer'];
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => Media::url($this->image));
    }
}
