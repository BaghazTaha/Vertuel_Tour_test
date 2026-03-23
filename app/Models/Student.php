<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'group_id', 'photo'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
