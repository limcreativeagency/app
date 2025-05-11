<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'Süper Yönetici',
                'slug' => 'super_admin',
                'description' => 'Sistem üzerinde tam yetkiye sahip süper yönetici',
                'is_active' => true,
            ],
            [
                'name' => 'Yönetici',
                'slug' => 'admin',
                'description' => 'Sistem yöneticisi',
                'is_active' => true,
            ],
            [
                'name' => 'Doktor',
                'slug' => 'doctor',
                'description' => 'Hastaları tedavi eden doktor',
                'is_active' => true,
            ],
            [
                'name' => 'Hemşire',
                'slug' => 'nurse',
                'description' => 'Hasta bakımını sağlayan hemşire',
                'is_active' => true,
            ],
            [
                'name' => 'Hasta',
                'slug' => 'patient',
                'description' => 'Tedavi gören hasta',
                'is_active' => true,
            ],
            [
                'name' => 'Resepsiyonist',
                'slug' => 'receptionist',
                'description' => 'Hasta kayıt ve randevu işlemlerini yöneten resepsiyonist',
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
