<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'source',
        'images',
        'download',
        'contributors',
        'release',
        'type',
        'user_id'
    ];

    protected $casts = [
        'images' => 'array',
        'contributors' => 'array',
        'release' => 'date:Y-m-d',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function likes() {
        return $this->hasMany(Like::class);
    }
    public function comments() {
        return $this->hasMany(Comment::class);
    }
}
