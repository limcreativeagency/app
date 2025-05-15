<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_name',
        'title', 'phone', 'email', 'address', 'city', 'country', 'website', 'status',
        'tax_number', 'description', 'notes', 'logo'
    ];
}
