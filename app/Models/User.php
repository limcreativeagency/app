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
        'language',
        'is_active',
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

    // Kullanıcının hasta olup olmadığını kontrol eden metod
    public function isPatient()
    {
        return $this->role->slug === 'patient';
    }

    // Kullanıcının doktor olup olmadığını kontrol eden metod
    public function isDoctor()
    {
        return $this->role->slug === 'doctor';
    }

    // Kullanıcının hemşire olup olmadığını kontrol eden metod
    public function isNurse()
    {
        return $this->role->slug === 'nurse';
    }

    // Kullanıcının yönetici olup olmadığını kontrol eden metod
    public function isAdmin()
    {
        return $this->role->slug === 'admin';
    }

    // Kullanıcının süper yönetici olup olmadığını kontrol eden metod
    public function isSuperAdmin()
    {
        return $this->role->slug === 'super_admin';
    }
}
