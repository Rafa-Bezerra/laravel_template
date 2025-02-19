<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Divisoes extends Authenticatable
{
    protected $table = "divisoes";
    protected $fillable = ['name'];
}
