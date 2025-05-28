<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicationUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'medication_plan_id',
        'medication_name',
        'dosage',
        'date',
        'time',
        'taken',
        'taken_at',
        'note',
        // Diğer alanlar da eklenebilir (ör: usage_time, used, vb.)
    ];

    public function plan()
    {
        return $this->belongsTo(MedicationPlan::class, 'medication_plan_id');
    }
}
