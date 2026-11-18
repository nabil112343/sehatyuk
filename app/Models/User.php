<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'phone', 'password', 'role', 'google_id'];
    protected $hidden = ['password', 'remember_token'];

    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'petugas']);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
