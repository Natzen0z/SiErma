<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppContext extends Model
{
    protected $fillable = [
        'year',
        'bidang',
        'urusan',
        'opd',
        'sasaran',
        'indikator',
        'notify_until',
        'notify_targets',
    ];

    protected $casts = [
        'year' => 'integer',
        'notify_until' => 'datetime',
        'notify_targets' => 'array'
    ];
}
