<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'treatment_id',
        'title',
        'day_after_operation',
        'instructions',
        'status',
        'due_date',
        'is_custom'
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_custom' => 'boolean'
    ];

    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class);
    }
} 