<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public const DEFAULT_FEATURES = [
        'dashboard',
        'register',
        'matrix',
        'controls',
        'assessment',
    ];

    protected $fillable = [
        'name',
        'username',
        'nip',
        'email',
        'password',
        'password_plain',
        'role',
        'is_active',
        'unit',
        'sub_unit',
        'bidang',
        'features',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'features' => 'array',
        ];
    }

    /**
     * Default sidebar tabs for regular users.
     */
    public static function defaultFeatures(): array
    {
        return self::DEFAULT_FEATURES;
    }

    /**
     * Whether this user may access a sidebar tab (admins/auditors see all).
     */
    public function hasFeature(string $feature): bool
    {
        if ($this->isAdmin() || $this->isAuditor() || $this->email === 'direktur@rsudmurjani.id') {
            return true;
        }

        $features = $this->features ?? self::defaultFeatures();

        return in_array($feature, $features, true);
    }

    /**
     * Check if user is a super admin (can see all units)
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' && empty($this->unit) && $this->units->isEmpty();
    }

    /**
     * Check if user is an auditor
     */
    public function isAuditor(): bool
    {
        return $this->role === 'auditor';
    }

    /**
     * Check if user is a unit admin (can only see their unit)
     */
    public function isUnitAdmin(): bool
    {
        return $this->role === 'admin' && (!empty($this->unit) || $this->units->isNotEmpty());
    }

    /**
     * Check if user is a Wadir
     */
    public function isWadir(): bool
    {
        if ($this->role !== 'admin') return false;

        if (!empty($this->unit) && (str_contains($this->unit, 'Wadir') || str_contains($this->unit, 'Wakil Direktur'))) {
            return true;
        }

        return $this->units->contains(function ($u) {
            return str_contains($u->name, 'Wadir') || str_contains($u->name, 'Wakil Direktur');
        });
    }

    /**
     * Check if user has access to a specific unit
     */
    public function hasAccessToUnit(string $unitName): bool
    {
        // Super admin has access to all units
        if ($this->isAdmin()) {
            return true;
        }

        // Check legacy column
        if ($this->unit === $unitName) {
            return true;
        }

        // Check pivot relationship
        return $this->units->contains('name', $unitName);
    }

    /**
     * Check if user is restricted to a specific unit (true for all except SuperAdmin/Direktur)
     */
    public function isRestrictedToUnit(): bool
    {
        return !$this->isAdmin() && $this->email !== 'direktur@rsudmurjani.id';
    }

    /**
     * Get primary unit for backward compatibility
     */
    public function getPrimaryUnitAttribute()
    {
        if (!empty($this->unit)) {
            return $this->unit;
        }

        $first = $this->units->first();
        return $first ? $first->name : null;
    }

    /**
     * Get all unit names for the user (legacy + relational)
     */
    public function getUnitNames(): array
    {
        $units = $this->units->pluck('name')->toArray();
        if (!empty($this->unit) && !in_array($this->unit, $units)) {
            $units[] = $this->unit;
        }
        return $units;
    }

    /**
     * Get the units this user belongs to
     */
    public function units()
    {
        return $this->belongsToMany(Unit::class);
    }

    /**
     * Get the risks owned by the user
     */
    public function risks()
    {
        return $this->hasMany(Risk::class);
    }
}


