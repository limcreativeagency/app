<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HospitalSeeder extends Seeder
{
    public function run()
    {
        $hospital = Hospital::create([
            'clinic_name' => "Klinik 1",
            'phone' => "+90555110001",
            'email' => "klinik1@test.com",
            'tax_number' => '12345678901',
            'address' => "Adres 1",
            'city' => "Şehir 1",
            'country' => "Türkiye",
            'trial_start_date' => now()->subDays(10),
            'trial_end_date' => now()->addDays(4),
            'subscription_start_date' => null,
            'subscription_end_date' => null,
            'status' => 'trial',
            'logo' => null,
            'description' => "Klinik 1 açıklaması.",
            'website' => "www.klinik1.com",
            'notes' => "Notlar 1",
        ]);

        User::create([
            'name' => "Klinik 1 Yöneticisi",
            'email' => "yonetici1@example.com",
            'phone' => "+90555000001",
            'password' => Hash::make('password'),
            'role_id' => 2, // Yönetici rolü
            'hospital_id' => $hospital->id,
            'is_active' => true,
        ]);
    }
} 