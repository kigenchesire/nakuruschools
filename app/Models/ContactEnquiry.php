<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactEnquiry extends Model
{
    use SoftDeletes;

    /** status => [label, Bootstrap badge colour, icon] */
    public const STATUSES = [
        'unread' => ['Unread', 'danger', 'bi-envelope-fill'],
        'read' => ['Read', 'secondary', 'bi-envelope-open'],
        'responded' => ['Responded', 'success', 'bi-reply-fill'],
        'archived' => ['Archived', 'dark', 'bi-archive'],
    ];

    protected $fillable = ['name', 'email', 'phone', 'subject', 'message', 'status', 'ip_address', 'user_agent', 'read_at'];

    protected function casts(): array
    {
        return ['read_at' => 'datetime'];
    }

    public function markAsRead(): void
    {
        if ($this->status === 'unread') {
            $this->forceFill(['status' => 'read', 'read_at' => now()])->save();
        }
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::get(fn () => self::STATUSES[$this->status][0] ?? ucfirst($this->status));
    }

    protected function statusColor(): Attribute
    {
        return Attribute::get(fn () => self::STATUSES[$this->status][1] ?? 'secondary');
    }

    protected function statusIcon(): Attribute
    {
        return Attribute::get(fn () => self::STATUSES[$this->status][2] ?? 'bi-circle');
    }
}
