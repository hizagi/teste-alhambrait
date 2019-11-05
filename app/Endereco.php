<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    const CREATED_AT = 'data_cadastro';
    const UPDATED_AT = 'data_alteracao';
    protected $fillable = [
        'id_cliente',
        'rua',
        'numero',
        'cep',
        'id_cidade',
        'id_estado',
        'data_cadastro',
        'data_alteracao',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id');
    }
}
