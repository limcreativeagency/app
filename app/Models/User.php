<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'country_code',
        'is_active',
        'clinic_id',
        'remember_token',
        'identity_number',
        'birth_date',
        'gender',
        'address',
        'city',
        'country',
        'postal_code',
        'medical_history',
        'allergies',
        'chronic_diseases',
        'blood_type',
        'emergency_contact_name',
        'emergency_contact_phone',
        'profile_photo',
        'notes',
        'hospital_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'birth_date' => 'date',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    /**
     * Check if user has a specific role or roles
     *
     * @param string|array $roles Role slug(s) to check
     * @return bool
     */
    public function hasRole($roles)
    {
        // If role relationship is not loaded, load it
        if (!$this->relationLoaded('role')) {
            $this->load('role');
        }

        // If role is null, return false
        if (!$this->role) {
            return false;
        }

        if (is_string($roles)) {
            return $this->role->slug === $roles;
        }

        if (is_array($roles)) {
            return in_array($this->role->slug, $roles);
        }

        return false;
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->role && in_array($this->role->slug, $roles);
    }

    // Kullanıcının hasta olup olmadığını kontrol eden metod
    public function isPatient()
    {
        return $this->hasRole('patient');
    }

    // Kullanıcının doktor olup olmadığını kontrol eden metod
    public function isDoctor()
    {
        return $this->hasRole('doctor');
    }

    // Kullanıcının hemşire olup olmadığını kontrol eden metod
    public function isNurse()
    {
        return $this->hasRole('nurse');
    }

    // Kullanıcının yönetici olup olmadığını kontrol eden metod
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    // Kullanıcının süper yönetici olup olmadığını kontrol eden metod
    public function isSuperAdmin()
    {
        return $this->hasRole('super_admin');
    }
}
