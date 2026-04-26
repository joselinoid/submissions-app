<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleHasPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::where('key', 'ADMIN')->first();

        $applicant = Role::where('key', 'PEMOHON')->first();

        $pejabats = Role::whereIn('key', [
            'PEJABAT1',
            'PEJABAT2',
            'PEJABAT3',
            'PEJABAT4'
        ])->get();

        $allPermissions = Permission::all();

        $adminPermissions = $allPermissions->filter(function ($perm) {
            return in_array($perm->group, [
                'categories',
                'users',
                'role-permission'
            ]);
        });

        $admin->permissions()->sync($adminPermissions->pluck('id'));


        $submissionPermissions = $allPermissions->filter(function ($perm) {
            return $perm->group === 'submissions';
        });

        $applicant->permissions()->sync($submissionPermissions->pluck('id'));

        foreach ($pejabats as $role) {
            $role->permissions()->sync($submissionPermissions->pluck('id'));
        }
    }
}
