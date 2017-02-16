<?php

namespace App\Http\Middleware;

use App\Helpers\Result;
use App\User;
use Closure;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $auth_token = $request->header('Authorization');
        $user = User::where('remember_token', '=', $auth_token)
            ->first();
        if(!($user instanceof User)){
            Result::error(401, 'Пользователь не найден');

        } else {
            if ($user->role == 1) {
                return $next($request);
            } else {
                Result::error(401, 'У Вас нет полномочий');

            }
        }

    }
}
