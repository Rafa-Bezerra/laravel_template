<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Bancos;
use App\Models\Orcamentos;

class Pagamentos extends BaseModel
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $table = 'pagamentos';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'orcamento_id',
        'compra_id',
        'banco_id',
        'parcela',
        'data',
        'valor',
        'especie',
        'controle',
        'tipo_pagamento',
        'name',
    ];

    public function banco()
    {
        return $this->belongsTo(Bancos::class);
    }

    public function orcamento()
    {
        return $this->belongsTo(Orcamentos::class);
    }
}
