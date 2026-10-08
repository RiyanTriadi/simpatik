<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aspiration extends Model
{
    use HasFactory, SoftDeletes;

    public const CREATOR_INTERNAL = 'internal';
    public const CREATOR_EKSTERNAL = 'eksternal';

    public const STATUS_BARU = 'baru';
    public const STATUS_DITINDAKLANJUTI = 'ditindaklanjuti';
    public const STATUS_SELESAI = 'selesai';
    public const STATUS_DITOLAK = 'ditolak';

    protected $fillable = [
        'ticket_number',
        'creator_type',
        'is_anonymous',
        'subject',
        'description',
        'attachment_path',
        'reporter_name',
        'reporter_phone',
        'reporter_email',
        'status',
        'category_id',
    ];

    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
        ];
    }

    // Relasi
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Scope
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function getCreatorLabelAttribute(): string
    {
        return $this->creator_type === self::CREATOR_INTERNAL ? 'Internal' : 'Eksternal';
    }

    public function getCreatorBadgeClassAttribute(): string
    {
        return $this->creator_type === self::CREATOR_INTERNAL
            ? 'text-teal-500'
            : 'text-indigo-500';
    }

    public function getRouteKeyName(): string
    {
        return 'ticket_number';
    }
}