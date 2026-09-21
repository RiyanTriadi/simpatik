<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    public const TYPE_KEDUANYA = 'keduanya';
    public const TYPE_PENGADUAN = 'pengaduan';
    public const TYPE_ASPIRASI = 'aspirasi';

    protected $fillable = [
        'name',
        'type',
        'description',
        'color',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Relasi
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function aspirations(): HasMany
    {
        return $this->hasMany(Aspiration::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}