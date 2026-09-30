<?php

namespace App\Models;

use App\Models\Concerns\HasStatusAndOrder;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasStatusAndOrder;

    protected $fillable = ['question', 'answer', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'sort_order' => 'integer'];
    }
}
