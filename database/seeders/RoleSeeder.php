<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'id' => Str::uuid(),
            'key' => 'PEMOHON',
            'label' => 'Pemohon',
            'description' => 'User yang ingin membuat pengajuan',
        ]);

        Role::create([
            'id' => Str::uuid(),
            'key' => 'PEJABAT1',
            'label' => 'Pejabat 1',
        ]);

        Role::create([
            'id' => Str::uuid(),
            'key' => 'PEJABAT2',
            'label' => 'Pejabat 2',
        ]);

        Role::create([
            'id' => Str::uuid(),
            'key' => 'PEJABAT3',
            'label' => 'Pejabat 3',
        ]);

        Role::create([
            'id' => Str::uuid(),
            'key' => 'PEJABAT4',
            'label' => 'Pejabat 4',
        ]);

        Role::create([
            'id' => Str::uuid(),
            'key' => 'PEJABAT5',
            'label' => 'Pejabat 5',
        ]);

        Role::create([
            'id' => Str::uuid(),
            'key' => 'ADMIN',
            'label' => 'Admin',
            'description' => 'Administrator sistem',
        ]);
    }
}
