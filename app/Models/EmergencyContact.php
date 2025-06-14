<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'name',
        'phone',
        'relation'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
