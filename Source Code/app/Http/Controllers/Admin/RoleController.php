<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Store;
use Auth;
use DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
// use App\Repository\Permission\PermissionInterface;
// use App\Repository\Role\RoleInterface;
// use Illuminate\Support\Facades\Log;
// use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Toastr;

class RoleController extends Controller
{
    // public function __construct(RoleInterface $roles, PermissionInterface $permissions)
    // {
    //     $this->roles = $roles;
    //     $this->permissions = $permissions;
    // }


    public function index(Request $request)
    {
        $roles = Role::paginate(10);
        return view('admin.roles.index', [
            'title' => 'Roles',
            'root_name' => 'All Roles',
            'root' => 'All Roles',
            'roles' => $roles
        ]);
    }

    public function show(Request $request)
    {
        $id = $request->role;
        $role = Role::find($id);
        if ($request->wantsJson()) {
            // $all_permission = [];
            // foreach ($role->getAllPermissions() as $permission) {
            //     array_push($all_permission, $permission->name);
            // }
            return $role->getAllPermissions();
        }
        return view('admin.roles.show', [
            'title' => 'Roles',
            'root_name' => 'All Roles',
            'root' => 'All Roles',
            'role' => $role
        ]);
    }

    public function create(Request $request)
    {
        $permissions = Permission::get();
        return view('admin.roles.create', [
            'title' => 'Roles',
            'root_name' => 'All Roles',
            'root' => 'All Roles',
            'permissions' => $permissions
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
        ]);
        $data = [
            "name" => $request->name
        ];
        $role = Role::create($data);

        $role->syncPermissions($request->input('permissions'));
        return redirect()->route('roles.index')->with(Toastr::success('Role Created successfully ', 'Success'));
    }

    public function edit($role_id)
    {
        $id = $role_id;
        $role = Role::find($id);
        $permissions = Permission::all();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
        return view('admin.roles.edit', [
            'title' => 'Roles',
            'root_name' => 'All Roles',
            'root' => 'All Roles',
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
    }

    public function update(Request $request, $role_id)
    {
        $this->validate($request, [
            'name'        => 'required|max:50|unique:roles,name,' . $role_id,
            'permissions' => 'required',
        ]);

        $input = $request->except(['permissions']);
        $permissions = $request['permissions'];
        $role = Role::findOrFail($role_id);
        $role->fill($input)->save();

        $p_all = Permission::all(); //Get all permissions

        foreach ($p_all as $p) {
            $role->revokePermissionTo($p); //Remove all permissions associated with role
        }

        foreach ($permissions as $permission) {
            $role->givePermissionTo(Permission::find($permission));  //Assign permission to role
        }

        $users = User::whereHas('roles', function ($q) use ($role) {
            $q->where('id', $role->id);
        })->get();

        $stores = Store::whereHas('roles', function ($q) use ($role) {
            $q->where('id', $role->id);
        })->get();

        foreach ($users as $user) {
            $user->syncPermissions($permissions);
        }

        foreach ($stores as $store) {
            $store->syncPermissions($permissions);
        }
        return redirect()->route('roles.index')->with(Toastr::success('Role Updated successfully ', 'Success'));
    }

    public function destroy(Request $request, $role_id)
    {
        $id = $role_id;
        $role = Role::findOrFail($id);
        $user_roles = auth()->user()->roles()->pluck('id');
        $role_users = $role->users;
        if ($id == 1) {
            $request->session()->flash('not-deleted', " You can not delete Administrator!");
            return redirect()->route('roles.index');
        } elseif (in_array($id, $user_roles->toArray())) {
            $request->session()->flash('not-deleted', " You can not delete your Role!");
            return redirect()->route('roles.index');
        } elseif ($role_users->count()) {
            $request->session()->flash('not-deleted', " Can not be deleted! - " . $role_users->count() . " user found");
            return redirect()->route('roles.index');
        }

        try {
            if ($role->delete()) {
                return redirect()->route('roles.index')->with(Toastr::success('Role Deleted successfully ', 'Success'));
            }
        } catch (\Exception $id) {
            return redirect()->route('roles.index')->with(Toastr::error('Something went wrong!', 'Error'));
        }
    }
}
