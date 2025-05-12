<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Hospital;
use Illuminate\Support\Facades\Hash;

class HospitalAndAdminSeeder extends Seeder
{
    public function run()
    {
        // Klinik oluştur
        $hospital = Hospital::create([
            'title' => 'Örnek Klinik',
            'phone' => '+905551112233',
            'email' => 'klinik@example.com',
            'address' => 'İstanbul, Türkiye',
            'city' => 'İstanbul',
            'country' => 'Türkiye',
            'website' => 'www.ornekklink.com',
            'status' => 'active',
        ]);

        // Süper Admin oluştur
        $superAdmin = User::create([
            'name' => 'Süper Admin',
            'email' => 'superadmin@example.com',
            'phone' => '+905550000001',
            'password' => Hash::make('password'),
            'role_id' => 1, // Süper admin rolü
            'hospital_id' => $hospital->id,
            'is_active' => true,
        ]);

        // Yönetici oluştur
        $manager = User::create([
            'name' => 'Klinik Yöneticisi',
            'email' => 'yonetici@example.com',
            'phone' => '+905550000002',
            'password' => Hash::make('password'),
            'role_id' => 2, // Yönetici rolü
            'hospital_id' => $hospital->id,
            'is_active' => true,
        ]);
    }
} 