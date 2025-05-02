<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Orcamentos;
use App\Models\Bancos;

class OrcamentosGastos extends BaseModel
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $table = 'orcamentos_gastos';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'orcamento_id',
        'tipo_pagamento',
        'valor',
        'data',
        'controle',
        'banco_id',
        'observacao',
        'especie',
    ];

    public function orcamento()
    {
        return $this->belongsTo(Orcamentos::class);
    }

    public function banco()
    {
        return $this->belongsTo(Bancos::class);
    }
}
