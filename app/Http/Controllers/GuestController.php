<?php

namespace App\Http\Controllers;

use App\Helpers\Result;
use App\User;
use Illuminate\Http\Request;

class GuestController extends Controller
{

   public function loginUsers(){
        $users = User::where('is_active', '=', 1)
            ->with('roleName')
            ->get();

        return view('login', [
            'users' => $users,
        ]);
    }

    public function login(Request $request)
    {
        $id = $request->get('id');
        $password = $request->get('password');
        $user = User::where('id', '=', $id)
            ->first();
        if ($user instanceof User) {
            if ($user->password === md5($password)) {
                $user->remember_token = $this->generateToken();
                $user->save();
                return [
                    'auth_token' => $user->remember_token
                ];
            } else {
                Result::error(401, 'Неверный пароль');
            }
        }
        Result::error(401, 'Выберите пользователя');
    }

    private function generateToken($length = 32)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
