<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Toastr;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::paginate(10);
        return view('admin.users.index', [
            'title' => 'Admin',
            'root_name' => 'All Admin',
            'root' => 'All Admin',
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $roles = Role::all();
        return view('admin.users.create', [
            'title' => 'Admin',
            'root_name' => 'Add Admin',
            'root' => 'Add Admin',
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'email|required|unique:users,email',
            'password' => 'min:4|max:255|required',
            'role' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation->errors());
        } else {
            $inputs = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request['password']),
                'is_active' => 1,
                'email_verified_at' => date(now())
            ];
            $user = User::create($inputs);
            if (!empty($inputs)) {
                $user->assignRole($request->input('role'));
                $roles = $request->input('role');
                $rolePermissions = DB::table("roles")->where("roles.id", $roles)
                    ->join("role_has_permissions", "roles.id", "role_has_permissions.role_id")
                    ->pluck('role_has_permissions.permission_id')
                    ->all();
                $user->syncPermissions($rolePermissions);
                $request->session()->flash('user-created-message', 'User was successfully created');
            }
            return redirect()->route('user.index')->with(Toastr::success('Admin Created successfully ', 'Success'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user = User::find($id);
        return view('admin.users.show', [
            'title' => 'Admin',
            'root_name' => 'Add Admin',
            'root' => 'Add Admin',
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::find($id);
        $roles = Role::all();
        return view('admin.users.create', [
            'title' => 'Admin',
            'root_name' => 'Add Admin',
            'root' => 'Add Admin',
            'roles' => $roles,
            'data' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'email|required|unique:users,email,' . $id,
            'role' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation->errors());
        } else {
            $inputs = [
                'name' => $request->name,
                'email' => $request->email,
                'is_active' => 1,
                'email_verified_at' => date(now())
            ];
            $user = User::find($id);
            $user->update($inputs);
            if (!empty($inputs)) {
                if (!empty($request->input('role'))) {
                    DB::table('model_has_roles')->where('model_id', $id)->delete();
                    $user->assignRole($request->input('role'));
                    $roles = $request->input('role');
                    $rolePermissions = DB::table("roles")->where("roles.id", $roles)
                        ->join("role_has_permissions", "roles.id", "role_has_permissions.role_id")
                        ->pluck('role_has_permissions.permission_id')
                        ->all();
                    $user->syncPermissions($rolePermissions);
                    $request->session()->flash('user-created-message', 'User was successfully created');
                }
            }
            return redirect()->route('user.index')->with(Toastr::success('Admin Updated successfully ', 'Success'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        User::find($id)->delete();
        return redirect()->route('user.index')->with(Toastr::success('Admin Deleted successfully ', 'Success'));
    }

    public function changePassword($user_id)
    {
        return view('admin.users.changePassword', [
            'title' => 'Change Password',
            'root_name' => 'Change Password',
            'root' => 'Change Password',
            'user_id' => $user_id
        ]);
    }
    public function updatePassword(Request $request, $user_id)
    {
        $validation = Validator::make($request->all(), [
            'password' => 'required',
            're-password' => 'required|same:password',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation->errors());
        } else {
            $password = Hash::make($request->password);
            $user = User::find($user_id);
            if ($user->update(['password' => $password]))
                return redirect()->route('user.index')->with(Toastr::success('Password Updated successfully ', 'Success'));
        }
    }
}
