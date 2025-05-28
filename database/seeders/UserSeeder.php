<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = User::create([
            'name' => 'Superadmin',
            'email' => 'superadmin@damaidiri.com',
            'email_verified_at' => now(),
            'remember_token' => Str::random(60),
            'password' => bcrypt('superadmin123'),
            'is_actived' => 1,
        ]);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@damaidiri.com',
            'email_verified_at' => now(),
            'remember_token' => Str::random(60),
            'password' => bcrypt('admin123'),
            'is_actived' => 1,
        ]);

        $patient = User::create([
            'name' => 'Patient',
            'email' => 'patient@damaidiri.com',
            'email_verified_at' => now(),
            'remember_token' => Str::random(60),
            'password' => bcrypt('patient123'),
            'is_actived' => 1,
        ]);

        $psychologist = User::create([
            'name' => 'Psikolog',
            'email' => 'psikolog@damaidiri.com',
            'email_verified_at' => now(),
            'remember_token' => Str::random(60),
            'password' => bcrypt('psikolog123'),
            'is_actived' => 1,
        ]);

        $superadmin->assignRole(User::SUPERADMIN_ROLE);
        $admin->assignRole(User::ADMIN_ROLE);
        $patient->assignRole(User::PATIENT_ROLE);
        $psychologist->assignRole(User::PSYCHOLOGIST_ROLE);

        // Account Demo Patient & Psychologist
        $names = ['Linda', 'Aliefia', 'Rafli', 'Fachril', 'Lola', 'Umi', 'Sakina', 'Anik'];
        foreach ($names as $name) {
            $user = User::create([
                'name' => $name,
                'email' => strtolower($name) . '-pasien@damaidiri.com',
                'email_verified_at' => now(),
                'remember_token' => Str::random(60),
                'password' => bcrypt('12345678'),
                'is_actived' => 1,
            ]);

            $user->assignRole(User::PATIENT_ROLE);
        }

        foreach ($names as $name) {
            $user = User::create([
                'name' => $name,
                'email' => strtolower($name) . '-psikolog@damaidiri.com',
                'email_verified_at' => now(),
                'remember_token' => Str::random(60),
                'password' => bcrypt('12345678'),
                'is_actived' => 1,
            ]);

            $user->assignRole(User::PSYCHOLOGIST_ROLE);
        }
    }
}
