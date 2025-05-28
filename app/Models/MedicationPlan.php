<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicationPlan extends Model
{
    use HasFactory;

    protected $casts = [
        'times' => 'array',
    ];

    protected $fillable = [
        'treatment_id',
        'user_id',
        'name',
        'type',
        'dose',
        'instructions',
        'times',
        'start_date',
        'end_date',
        'notes',
    ];

    public function usages()
    {
        return $this->hasMany(MedicationUsage::class);
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    protected static function booted()
    {
        static::created(function ($plan) {
            \App\Services\MedicationUsageGenerator::generate($plan);
        });

        static::deleting(function ($plan) {
            // İlişkili tüm kullanım kayıtlarını sil
            $plan->usages()->delete();
        });
    }
}
