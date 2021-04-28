<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'provider_id',
        'avatar',
        'slug',
        'links'
    ];
    protected $hidden = [
        'remember_token',
        'provider_id'
    ];
    protected $casts = [
        'provider_id' => 'double',
        'links' => 'array'
    ];

    public function addons() {
        return $this->hasMany(Addon::class);
    }
    public function roles() {
        return $this->belongsToMany(Role::class);
    }
    public function likes() {
        return $this->hasMany(Like::class);
    }
    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function getRoles() {
        $roles = [];
        foreach ($this->roles()->get() as $role) {
            $roles[] = $role['name'];
        }
        return $roles;
    }
}
