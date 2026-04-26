<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:role-permission.view')->only(['index', 'show']);
        $this->middleware('permission:role-permission.create')->only(['create', 'store']);
        $this->middleware('permission:role-permission.update')->only(['edit', 'update']);
        $this->middleware('permission:role-permission.delete')->only(['destroy']);
    }

    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('role-permission.index', compact('roles', 'permissions'));
    }
}
