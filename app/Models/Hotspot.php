<?php
// app/Models/Hotspot.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hotspot extends Model
{
    protected $fillable = [
        'space_id',
        'employee_id',
        'target_scene_id',
        'type',
        'label',
        'pitch',
        'yaw',
    ];

    protected $casts = [
        'pitch' => 'float',
        'yaw'   => 'float',
    ];

    /* ---------- Relationships ---------- */

    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function targetScene(): BelongsTo
    {
        return $this->belongsTo(Space::class, 'target_scene_id');
    }
}