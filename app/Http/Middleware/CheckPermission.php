<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Roles;
use App\Models\Actions;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $user = User::find($user->id);
        $actions = $user->actions();
        $roles = $user->roles;
        $admin = false;
        foreach ($roles as $key => $value) {
            if($value->id == 1) $admin = true;
        }

        if(! $admin) {

        }

        // if ($user && empty($user->password)) {
        //     return redirect()->route('password.change'); // Rota para alteração de senha
        // }

        return $next($request);
    }
}
