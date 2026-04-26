<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pemohonRole = Role::where('key', 'PEMOHON')->first();
        $pejabat1Role = Role::where('key', 'PEJABAT1')->first();
        $pejabat2Role = Role::where('key', 'PEJABAT2')->first();
        $pejabat3Role = Role::where('key', 'PEJABAT3')->first();
        $pejabat4Role = Role::where('key', 'PEJABAT4')->first();
        $adminRole = Role::where('key', 'ADMIN')->first();

        User::create([
            'name' => 'Muhammad Idrus',
            'email' => 'm.idrus@example.com',
            'role_id' => $pemohonRole->id,
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'Alexander Asep',
            'email' => 'alex@example.com',
            'role_id' => $pemohonRole->id,
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'pejabat1@example.com',
            'role_id' => $pejabat1Role->id,
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'Siti Nurjanah',
            'email' => 'pejabat2@example.com',
            'role_id' => $pejabat2Role->id,
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'pejabat3@example.com',
            'role_id' => $pejabat3Role->id,
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'Ratna Dewi',
            'email' => 'pejabat4@example.com',
            'role_id' => $pejabat4Role->id,
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'role_id' => $adminRole->id,
            'password' => Hash::make('12345678'),
        ]);
    }
}
