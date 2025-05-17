<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_name',
        'phone',
        'email',
        'tax_number',
        'address',
        'city',
        'country',
        'website',
        'description',
        'notes',
        'logo',
        'trial_start_date',
        'trial_end_date',
        'subscription_start_date',
        'subscription_end_date',
        'status'
    ];

    protected $casts = [
        'trial_start_date' => 'datetime',
        'trial_end_date' => 'datetime',
        'subscription_start_date' => 'datetime',
        'subscription_end_date' => 'datetime'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function manager()
    {
        return $this->hasOne(User::class)->where('role_id', 2);
    }
}
