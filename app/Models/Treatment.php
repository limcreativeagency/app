<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Treatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'user_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        'treatment_type',
        'treatment_area',
        'graft_count',
        'operation_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'treatment_area' => 'array',
        'operation_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function stages(): HasMany
    {
        return $this->hasMany(TreatmentStage::class);
    }

    public function photos()
    {
        return $this->hasMany(\App\Models\TreatmentPhoto::class, 'treatment_id')->orderBy('created_at', 'desc');
    }

    public function medicationPlans()
    {
        return $this->hasMany(\App\Models\MedicationPlan::class);
    }

    public function notes()
    {
        return $this->hasMany(TreatmentNote::class);
    }

    public function getVisibleNotes()
    {
        if (auth()->user()->hasRole('patient')) {
            return $this->notes()->visibleToPatient()->with(['user'])->get();
        }
        return $this->notes()->with(['user'])->get();
    }
}
