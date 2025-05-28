<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Hospital;
use App\Models\User;

class DemoSeeder extends Seeder
{
    public function run()
    {
        // 1. Demo hastane oluştur
        $hospital = Hospital::firstOrCreate([
            'clinic_name' => 'Demo Hastanesi',
        ], [
            'phone' => '+90 212 123 45 67',
            'email' => 'demo@hastane.com',
            'city' => 'İstanbul',
            'country' => 'Türkiye',
            'address' => 'Test Mah. Deneme Cad. No:1',
            'tax_number' => '1234567890',
        ]);

        // 2. Kullanıcılar
        $users = [
            [
                'role_id' => 2,
                'name' => 'Demo Klinik Yöneticisi',
                'email' => 'klinikyoneticisi@demo.com',
                'password' => Hash::make('password'),
            ],
            [
                'role_id' => 3,
                'name' => 'Demo Doktor',
                'email' => 'doktor@demo.com',
                'password' => Hash::make('password'),
            ],
            [
                'role_id' => 4,
                'name' => 'Demo Temsilci',
                'email' => 'temsilci@demo.com',
                'password' => Hash::make('password'),
            ],
            [
                'role_id' => 5,
                'name' => 'Demo Hasta',
                'email' => 'hasta@demo.com',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate([
                'email' => $userData['email'],
            ], array_merge($userData, [
                'hospital_id' => $hospital->id,
            ]));
        }

        $this->command->info('Demo veriler başarıyla oluşturuldu.');
    }
} 