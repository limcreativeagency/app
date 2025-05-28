<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentNote extends Model
{
    protected $fillable = [
        'treatment_id',
        'user_id',
        'note',
        'is_visible_to_patient'
    ];

    protected $casts = [
        'is_visible_to_patient' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeVisibleToPatient($query)
    {
        return $query->where('is_visible_to_patient', true);
    }
} 