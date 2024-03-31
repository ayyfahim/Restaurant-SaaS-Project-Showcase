<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repository\Permission\PermissionInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Toastr;

class PermissionController extends Controller
{
    // public function __construct(PermissionInterface $permissions)
    // {
    //     $this->permissions = $permissions;
    // }


    public function index(Request $request)
    {
        $permissions = Permission::paginate(10);
        return view('admin.permission.index', [
            'title' => 'Permissions',
            'root_name' => 'All Permissions',
            'root' => 'All Permissions',
            'permissions' => $permissions
        ]);
    }

    public function show(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        return view('admin.permission.show', [
            'title' => 'Permissions',
            'root_name' => 'All Permissions',
            'root' => 'All Permissions',
            'permission' => $permission
        ]);
    }

    public function create()
    {
        return view('admin.permission.create', [
            'title' => 'Permissions',
            'root_name' => 'All Permissions',
            'root' => 'All Permissions'
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
        ]);
        Permission::create($request->all());
        if ($role = Role::where('name', 'superadmin')->first()) {
            $role->syncPermissions(Permission::all());
        }
        return redirect()->route('permissions.index')->with(Toastr::success('Permission Created successfully ', 'Success'));
    }

    public function edit(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        return view('admin.permission.edit', [
            'title' => 'Permissions',
            'root_name' => 'All Permissions',
            'root' => 'All Permissions',
            'permission' => $permission
        ]);
    }

    public function update(Request $request, $permission_id)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
        ]);
        $permission = Permission::findOrFail($permission_id);
        $permission->update($request->all());
        return redirect()->route('permissions.index')->with(Toastr::success('Permission Updated successfully ', 'Success'));
    }

    public function destroy(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return redirect()->route('permissions.index')->with(Toastr::success('Permission Deleted successfully ', 'Success'));
    }
}
