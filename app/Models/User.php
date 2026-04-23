<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'sex',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'password'             => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }

    /* ---------- Relationships ---------- */

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function trainer(): HasOne
    {
        return $this->hasOne(Trainer::class);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    /* ---------- Helpers ---------- */

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isEmployee(): bool
    {
        return $this->hasRole('employee');
    }

    public function isTrainer(): bool
    {
        return $this->hasRole('trainer');
    }

    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    public static function generateEmail(string $firstName, string $lastName): string
    {
        $firstName = strtolower(trim($firstName));
        $lastName = strtolower(trim($lastName));
        
        $firstName = preg_replace('/\s+/', '.', $firstName);
        $lastName = preg_replace('/\s+/', '.', $lastName);
        
        return "{$firstName}.{$lastName}@company.com";
    }
}

/*


## Relationship Map (summary)
```
User          ──hasOne──►  Employee
Department    ──hasMany──► Employee
Department    ──hasMany──► Space
Space         ──hasMany──► Hotspot
Employee      ──hasMany──► Hotspot  (as subject)
Space         ──hasMany──► Hotspot  (as target_scene via target_scene_id)
  */