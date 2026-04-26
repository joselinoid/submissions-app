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

        /*
        ---------------------------------------
        ALL PERMISSIONS
        ---------------------------------------
        */
        $allPermissions = Permission::all();

        /*
        ---------------------------------------
        🚫 ADMIN: NO submissions.*
        ---------------------------------------
        */
        $adminPermissions = $allPermissions->reject(function ($perm) {
            return $perm->group === 'submissions';
        });

        $admin->permissions()->sync($adminPermissions->pluck('id'));

        /*
        ---------------------------------------
        👤 APPLICANT + PEJABAT:
        🚫 NO categories.*
        ---------------------------------------
        */
        $nonCategoryPermissions = $allPermissions->reject(function ($perm) {
            return $perm->group === 'categories';
        });

        $applicant->permissions()->sync($nonCategoryPermissions->pluck('id'));

        foreach ($pejabats as $role) {
            $role->permissions()->sync($nonCategoryPermissions->pluck('id'));
        }
    }
}
