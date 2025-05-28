<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
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
        'medications_used',
        'blood_type',
        'notes',
        'height',
        'weight',
        'smoking_status',
        'alcohol_consumption',
        'exercise_status',
        'dietary_habits',
        'occupation',
        'marital_status',
        'is_verified',
        'verification_code',
        'verification_code_expires_at',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'allergies' => 'json',
        'chronic_diseases' => 'json',
        'medications_used' => 'json',
        'is_verified' => 'boolean',
        'verification_code_expires_at' => 'datetime',
        'height' => 'integer',
        'weight' => 'integer'
    ];

    // Smoking status enum values
    const SMOKING_NEVER = 'never';
    const SMOKING_FORMER = 'former';
    const SMOKING_CURRENT = 'current';

    // Alcohol consumption enum values
    const ALCOHOL_NEVER = 'never';
    const ALCOHOL_OCCASIONAL = 'occasional';
    const ALCOHOL_REGULAR = 'regular';
    const ALCOHOL_FORMER = 'former';

    // Exercise status enum values
    const EXERCISE_NONE = 'none';
    const EXERCISE_OCCASIONAL = 'occasional';
    const EXERCISE_REGULAR = 'regular';

    // Marital status enum values
    const MARITAL_SINGLE = 'single';
    const MARITAL_MARRIED = 'married';
    const MARITAL_DIVORCED = 'divorced';
    const MARITAL_WIDOWED = 'widowed';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function emergencyContacts()
    {
        return $this->hasMany(EmergencyContact::class);
    }

    public function treatments()
    {
        return $this->hasMany(\App\Models\Treatment::class, 'patient_id');
    }

    // BMI calculation
    public function getBmiAttribute()
    {
        if ($this->height && $this->weight) {
            $heightMetre = $this->height / 100;
            return round($this->weight / ($heightMetre * $heightMetre), 2);
        }
        return null;
    }

    // BMI Status
    public function getBmiStatusAttribute()
    {
        $bmi = $this->bmi;
        if (!$bmi) return null;

        if ($bmi < 18.5) return 'Underweight';
        if ($bmi < 25) return 'Normal';
        if ($bmi < 30) return 'Overweight';
        return 'Obese';
    }
}
