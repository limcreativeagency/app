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
        'blood_type',
        'medications_used',
        'profile_photo',
        'notes'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'allergies' => 'array',
        'chronic_diseases' => 'array',
        'medications_used' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
