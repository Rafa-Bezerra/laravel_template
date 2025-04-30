<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Roles;
use Illuminate\Support\Facades\Auth;

class User extends BaseModel
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nickname',
        'active',
        'password',
        'password_expiration',
        'auth_token',
        'auth_token_expiration',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(
            Roles::class,
            'users_roles',
            'user_id',
            'role_id');
    }

    public function actions()
    {
        return $this->roles()->with('actions')->get()->pluck('actions')->flatten()->unique('id');
    }

    public static function hasPermission(String $route)
    {
        $user = Auth::user();
        $user = User::find($user->id);
        $actions = $user->actions();
        $roles = $user->roles;
        $permission = false;
        foreach ($roles as $key => $value) {
            if($value->id == 1) $permission = true;
        }

        if(! $permission) {
            foreach ($actions as $key => $value) {
                if (strtoupper($value->route) == strtoupper($route)) {
                    $permission = true;
                }
            }
        }
        
        return $permission;
    }
}
