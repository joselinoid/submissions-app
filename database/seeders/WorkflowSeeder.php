<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Workflow;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pemohonId = Role::where('key', 'PEMOHON')->first()->id;
        $pejabat1Id = Role::where('key', 'PEJABAT1')->first()->id;
        $pejabat2Id = Role::where('key', 'PEJABAT2')->first()->id;
        $pejabat3Id = Role::where('key', 'PEJABAT3')->first()->id;
        $pejabat4Id = Role::where('key', 'PEJABAT4')->first()->id;

        Workflow::create([
            'id' => Str::uuid(),
            'key' => 'SUBMITTED',
            'label' => 'Pengajuan Permohonan',
            'step_order' => 1,
            'role_id' => $pemohonId,
        ]);

        Workflow::create([
            'id' => Str::uuid(),
            'key' => 'WAITING_P1_APPROVAL',
            'label' => 'Menunggu Persetujuan Pejabat 1',
            'step_order' => 2,
            'role_id' => $pejabat1Id,
        ]);

        Workflow::create([
            'id' => Str::uuid(),
            'key' => 'CHECK_BY_P2',
            'label' => 'Pemeriksaan Tahap 1',
            'step_order' => 3,
            'role_id' => $pejabat2Id,
        ]);

        Workflow::create([
            'id' => Str::uuid(),
            'key' => 'CHECK_BY_P3',
            'label' => 'Pemeriksaan Tahap 2',
            'step_order' => 4,
            'role_id' => $pejabat3Id,
        ]);

        Workflow::create([
            'id' => Str::uuid(),
            'key' => 'WAITING_P4_APPROVAL',
            'label' => 'Menunggu Persetujuan Pejabat 4',
            'step_order' => 5,
            'role_id' => $pejabat4Id,
        ]);

        Workflow::create([
            'id' => Str::uuid(),
            'key' => 'RETURNED_TO_P3',
            'label' => 'Menunggu Persetujuan Pejabat 3',
            'step_order' => 6,
            'role_id' => $pejabat3Id,
        ]);

        Workflow::create([
            'id' => Str::uuid(),
            'key' => 'RETURNED_TO_P2',
            'label' => 'Menunggu Persetujuan Pejabat 2',
            'step_order' => 7,
            'role_id' => $pejabat2Id,
        ]);

        Workflow::create([
            'id' => Str::uuid(),
            'key' => 'RETURNED_TO_APPLICANT',
            'label' => 'Terima Formulir Final dari Pejabat 2',
            'step_order' => 8,
            'role_id' => $pemohonId,
        ]);
    }
}
