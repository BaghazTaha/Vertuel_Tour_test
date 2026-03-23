<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = ['space_id', 'trainer_id', 'group_id', 'day_of_week', 'start_time', 'end_time', 'subject'];

    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
