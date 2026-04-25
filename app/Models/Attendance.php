<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'schedule_id',
        'student_id',
        'date',
        'status',
        'justification',
        'is_validated',
        'validated_at',
        'validated_by'
    ];

    protected $casts = [
        'date' => 'date',
        'is_validated' => 'boolean',
        'validated_at' => 'datetime',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
