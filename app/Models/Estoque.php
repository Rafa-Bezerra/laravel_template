<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Orcamentos;
use App\Models\Materiais;

class Estoque extends BaseModel
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $table = 'estoque';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'material_id',
        'quantidade',
        'orcamento_id',
        'valor',
    ];

    public function orcamento()
    {
        return $this->belongsTo(Orcamentos::class);
    }

    public function material()
    {
        return $this->belongsTo(Materiais::class);
    }
}
