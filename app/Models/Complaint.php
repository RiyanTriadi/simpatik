<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use HasFactory, SoftDeletes;

    public const CREATOR_INTERNAL = 'internal';
    public const CREATOR_EKSTERNAL = 'eksternal';

    public const STATUS_BARU = 'baru';
    public const STATUS_DIPROSES = 'diproses';
    public const STATUS_SELESAI = 'selesai';
    public const STATUS_DITOLAK = 'ditolak';

    public const PRIORITY_RENDAH = 'rendah';
    public const PRIORITY_SEDANG = 'sedang';
    public const PRIORITY_TINGGI = 'tinggi';
    public const PRIORITY_URGENT = 'urgent';

    protected $fillable = [
        'ticket_number',
        'creator_type',
        'category_id',
        'is_anonymous',
        'subject',
        'description',
        'incident_date',
        'incident_location',
        'attachment_path',
        'reporter_name',
        'reporter_phone',
        'reporter_email',
        'status',
        'priority',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
            'incident_date' => 'date',
            'resolved_at' => 'datetime',
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

    public function scopePriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    // Helper
    public function markAsResolved(): void
    {
        $this->update([
            'status' => self::STATUS_SELESAI,
            'resolved_at' => now(),
        ]);
    }
}