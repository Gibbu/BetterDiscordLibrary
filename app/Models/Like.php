<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'addon_id',
        'user_id'
    ];

    public function addon() {
        return $this->belongsTo(Addon::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}