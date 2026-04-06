<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    private const EXCLUDED_PERMISSIONS = [
        'view_dashboard',
        'view_analytics',
    ];

    public function index()
    {
        $roles = Role::with('permissions')
                    ->where('name', '!=', 'user')
                    ->get();
        $sidebarFeatures = $this->sidebarFeatures();

        return view('admin.roles.index', compact('roles', 'sidebarFeatures')); 
    }

    public function create()
    {
        $permissions = Permission::query()
            ->whereNotIn('name', self::EXCLUDED_PERMISSIONS)
            ->get();
        $sidebarFeatures = $this->sidebarFeatures();
        // Points to the separate create file
        return view('admin.roles.create', compact('permissions', 'sidebarFeatures'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array'
        ]);

        // 🌟 THIS IS THE FIX: Force the 'web' guard here!
        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web' // <-- Add this exact line
        ]);
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::query()
            ->whereNotIn('name', self::EXCLUDED_PERMISSIONS)
            ->get();
        $sidebarFeatures = $this->sidebarFeatures();
        // Point to the separate edit file
        return view('admin.roles.edit', compact('role', 'permissions', 'sidebarFeatures'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array'
        ]);

        // 🌟 FIX: Force the guard back to 'web' during the update process!
        $role->update([
            'name' => $request->name,
            'guard_name' => 'web' 
        ]);
        
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if($role->name === 'Super Admin') {
            return back()->with('error', 'Cannot delete the Super Admin role.');
        }
        
        $role->delete();
        return back()->with('success', 'Role deleted successfully.');
    }

    private function sidebarFeatures(): array
    {
        return [
            ['label' => 'Blood Requests',      'keywords' => ['request']],
            ['label' => 'Requested History',   'keywords' => ['history']],
            ['label' => 'Verify Invoices',     'keywords' => ['donation', 'invoice']],
            ['label' => 'Manage Users',        'keywords' => ['user']],
            ['label' => 'KYC Verifications',   'keywords' => ['kyc']],
            ['label' => 'Account Settings',    'keywords' => ['profile', 'account']],
            ['label' => 'Platform Settings',   'keywords' => ['setting']],
            ['label' => 'Roles & Permissions', 'keywords' => ['role', 'permission']],
        ];
    }
}