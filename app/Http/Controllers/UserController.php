<?php

namespace App\Http\Controllers;

use App\User;
use App\UserRole;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin_auth');
    }

    public function users()
    {
        $users = User::where('is_active', '=', 1)
            ->with('roleName')
            ->get();
        return $users;
    }

    public function userRoles()
    {
        $roles = UserRole::all();
        return $roles;
    }

    public function editUser(Request $request)
    {
        $id = $request->get('id');
        $name = $request->get('name');
        $role = $request->get('role');
        $password = $request->get('password');
        if (empty($id)) {
            $user = User::create([
                'name' => $name,
                'role' => $role,
                'password' => md5($password),
                'is_active' => 1
            ]);
            return $user;
        }
        $user = User::where('id', '=', $id)
            ->first();

        if ($user instanceof User) {
            $user->name = $name;
            $user->role = $role;
            if (!empty($password)) {
                $user->password = md5($password);
            }
            $user->save();
        }
        return $user;
    }

    public function deleteUser(Request $request)
    {
        $id = $request->get('id');
        $user = User::where('id', '=', $id)
            ->first();
        if ($user instanceof User) {
            $user->is_active = 0;
            $user->save();
        }
    }

}
