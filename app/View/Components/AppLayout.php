<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\UsersRoles;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $user = Auth::user();
        $isAdmin = UsersRoles::where('user_id', $user->id)->where('role_id', 1)->exists();
        $isInvestidor = UsersRoles::where('user_id', $user->id)->where('role_id', 8)->exists();
        return view('layouts.app', [
            'user' => $user,
            'isInvestidor' => $isInvestidor,
            'isAdmin' => $isAdmin,
        ]);
    }
}
