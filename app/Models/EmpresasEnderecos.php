<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Empresas;

class EmpresasEnderecos extends BaseModel
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $table = 'empresas_enderecos';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'empresa_id',
        'cep',
        'estado',
        'cidade',
        'rua',
        'numero',
        'complemento',
        'observacao',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresas::class);
    }
}
