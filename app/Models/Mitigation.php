<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mitigation extends Model
{
    protected $fillable = [
        'risk_id',
        'treatment',
        'status',
        'evidence_link',
    ];

    public function risk()
    {
        return $this->belongsTo(Risk::class);
    }
}
