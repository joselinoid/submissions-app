<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::create([
            'id' => Str::uuid(),
            'key' => 'PENDING',
            'label' => 'Menunggu',
        ]);

        Status::create([
            'id' => Str::uuid(),
            'key' => 'ACTIVE',
            'label' => 'Aktif',
        ]);

        Status::create([
            'id' => Str::uuid(),
            'key' => 'DONE',
            'label' => 'Selesai',
        ]);

        Status::create([
            'id' => Str::uuid(),
            'key' => 'REJECTED',
            'label' => 'Ditolak',
        ]);

        Status::create([
            'id' => Str::uuid(),
            'key' => 'COMPLETED',
            'label' => 'Selesai',
        ]);

        Status::create([
            'id' => Str::uuid(),
            'key' => 'REVISION',
            'label' => 'Revisi',
        ]);
    }
}
