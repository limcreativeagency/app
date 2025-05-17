<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Süper Admin rolünü bul
        $superAdminRole = Role::where('slug', 'super_admin')->first();

        // Süper Admin kullanıcısını oluştur
        if (!User::where('email', 'admin@admin.com')->exists()) {
            User::create([
                'name' => 'Süper Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role_id' => $superAdminRole->id,
                'is_active' => true,
                'phone' => '5555555555',
            ]);
        }
    }
} 