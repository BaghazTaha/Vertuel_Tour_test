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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /* ---------- Relationships ---------- */

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
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