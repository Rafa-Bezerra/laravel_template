<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Database\Factories\UsersRolesFactory;

class UsersRoles extends BaseModel
{
    use HasFactory;
    protected $table = "users_roles";
    protected $fillable = ['user_id','role_id'];

    protected static function newFactory(): Factory
    {
        return UsersRolesFactory::new();
    }
}
