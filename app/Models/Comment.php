<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'user_id',
        'reply_id',
        'pinned'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function addon() {
        return $this->belongsTo(Addon::class);
    }
    public function replies() {
        return $this->hasMany(Comment::class, 'reply_id');
    }
}
