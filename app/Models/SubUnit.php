<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubUnit extends Model
{
    protected $fillable = ['name', 'unit_name'];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_name', 'name');
    }
}
