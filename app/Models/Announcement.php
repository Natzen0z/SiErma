<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'bidang',
        'target_units',
        'target_users',
        'expires_at',
        'is_active'
    ];

    protected $casts = [
        'target_units' => 'array',
        'target_users' => 'array',
        'expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
