<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin kullanıcısı
        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'phone' => '+905551234567',
            'role_id' => 1, // Admin role
        ]);

        // Normal kullanıcılar
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "User {$i}",
                'email' => "user{$i}@test.com",
                'password' => Hash::make('password'),
                'phone' => "+90555" . str_pad($i, 7, '0', STR_PAD_LEFT),
                'role_id' => 3, // User role
            ]);
        }
    }
} 