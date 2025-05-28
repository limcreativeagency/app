<?php

namespace App\Services;

use App\Models\MedicationPlan;
use App\Models\MedicationUsage;
use Carbon\CarbonPeriod;
use Carbon\Carbon;

class MedicationUsageGenerator
{
    public static function generate(MedicationPlan $plan)
    {
        $period = CarbonPeriod::create($plan->start_date, $plan->end_date);

        foreach ($period as $date) {
            foreach ($plan->times as $time) {
                MedicationUsage::create([
                    'medication_plan_id' => $plan->id,
                    'medication_name' => $plan->name,
                    'dosage' => $plan->dose,
                    'date' => $date->toDateString(),
                    'time' => $time,
                    'taken' => false,
                    'instructions' => $plan->instructions ?? 'İlacı belirtilen dozda ve zamanda alınız.',
                    'start_date' => $plan->start_date
                ]);
            }
        }
    }

    public static function sync(MedicationPlan $plan)
    {
        // Mevcut kullanım kayıtlarını al
        $existingUsages = $plan->usages()->get();
        
        // Yeni tarih aralığını oluştur
        $period = CarbonPeriod::create($plan->start_date, $plan->end_date);
        $dates = collect($period)->map(fn($date) => $date->toDateString());
        
        // Planın times alanını array olarak al
        $planTimes = is_array($plan->times) ? $plan->times : json_decode($plan->times, true);
        
        // Silinecek kayıtları belirle
        $toDelete = $existingUsages->filter(function($usage) use ($planTimes, $dates) {
            return !in_array($usage->time, $planTimes) || !$dates->contains($usage->date);
        });
        
        // Silinecek kayıtları sil
        if ($toDelete->isNotEmpty()) {
            MedicationUsage::whereIn('id', $toDelete->pluck('id'))->delete();
        }
        
        // Yeni kayıtları oluştur
        foreach ($period as $date) {
            foreach ($planTimes as $time) {
                // Bu tarih ve saat için kayıt var mı kontrol et
                $exists = $existingUsages->contains(function($usage) use ($date, $time) {
                    return $usage->date === $date->toDateString() && $usage->time === $time;
                });
                
                // Yoksa yeni kayıt oluştur
                if (!$exists) {
                    MedicationUsage::create([
                        'medication_plan_id' => $plan->id,
                        'medication_name' => $plan->name,
                        'dosage' => $plan->dose,
                        'date' => $date->toDateString(),
                        'time' => $time,
                        'taken' => false,
                        'instructions' => $plan->instructions ?? 'İlacı belirtilen dozda ve zamanda alınız.',
                        'start_date' => $plan->start_date
                    ]);
                }
            }
        }
    }
} 