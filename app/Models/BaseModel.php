<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class BaseModel extends Authenticatable
{
    public function setAttribute($key, $value)
    {
        parent::setAttribute($key, is_string($value) ? strtoupper($value) : $value);
    }
}
