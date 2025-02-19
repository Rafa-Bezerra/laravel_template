<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Divisoes;
use App\Models\EmpresasEnderecos;
use App\Models\EmpresasContatos;

class Empresas extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $table = 'empresas';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'fantasia',
        'pessoa',
        'cpf',
        'cnpj',
        'divisao_id',
        'observacao',
    ];

    public function divisao(): BelongsTo
    {
        return $this->belongsTo(Divisoes::class);
    }

    public function enderecos(): HasMany
    {
        return $this->hasMany(EmpresasEnderecos::class,'empresa_id','id');
    }

    public function contatos(): HasMany
    {
        return $this->hasMany(EmpresasContatos::class,'empresa_id','id');
    }
}
