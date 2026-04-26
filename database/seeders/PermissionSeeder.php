<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'categories',
            'users',
            'submissions',
            'role-permission',
        ];

        $actions = [
            'view',
            'create',
            'update',
            'delete',
        ];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::create([
                    'key' => $module . '.' . $action,
                    'label' => ucfirst($action) . ' ' . ucfirst(str_replace('_', ' ', $module)),
                    'group' => $module,
                    'description' => ucfirst($action) . ' data ' . $module,
                ]);
            }
        }
    }
}
