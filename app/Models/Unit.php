<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'bidang'];

    public function subUnits()
    {
        return $this->hasMany(SubUnit::class, 'unit_name', 'name');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
