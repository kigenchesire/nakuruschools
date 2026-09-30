<?php

namespace App\Models;

use App\Models\Concerns\HasStatusAndOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasStatusAndOrder;

    public const GROUPS = [
        'why_choose_us' => 'Why Choose Us',
        'highlight' => 'Key Highlights',
    ];

    protected $fillable = [
        'group', 'title', 'value', 'description', 'icon', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'sort_order' => 'integer'];
    }

    public function scopeInGroup(Builder $query, string $group): Builder
    {
        return $query->where('group', $group);
    }
}
