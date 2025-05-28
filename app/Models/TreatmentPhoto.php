<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'treatment_id',
        'image_path',
        'photo_date',
        'photo_stage',
        'stage_type',
        'day_after_operation'
    ];

    protected $casts = [
        'photo_date' => 'date',
        'day_after_operation' => 'integer'
    ];

    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class);
    }
}
