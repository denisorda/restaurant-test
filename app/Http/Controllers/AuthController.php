<?php

namespace App\Http\Controllers;

use App\Helpers\Result;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function user(Request $request)
    {
        $auth_token = $request->header('Authorization');
        $user = User::where('remember_token', '=', $auth_token)
            ->with('roleName')
            ->first();
        if ($user instanceof User) {
            return $user;
        } else {
            Result::error(401, 'Пользователь не найден');
        }
    }

    public function logout(Request $request)
    {
        $auth_token = $request->header('Authorization');

        $user = User::where('remember_token', '=', $auth_token)
            ->first();
        if ($user instanceof User) {
            $user->remember_token = '';
            $user->save();
        }
    }

}
