<?php
// app/Models/Space.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Space extends Model
{
    protected $fillable = [
        'department_id',
        'name',
        'description',
        'photo_360_path',
        'thumbnail_path',
    ];

    /* ---------- Relationships ---------- */

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function hotspots(): HasMany
    {
        return $this->hasMany(Hotspot::class);
    }

    /* ---------- Accessors ---------- */

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_360_path
            ? asset('storage/' . $this->photo_360_path)
            : null;
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path
            ? asset('storage/' . $this->thumbnail_path)
            : null;
    }
}