<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Cliente extends Model
{
    const CREATED_AT = 'data_cadastro';
    const UPDATED_AT = 'data_alteracao';
    protected $fillable = [
        'nome',
        'data_nascimento',
        'data_cadastro',
        'data_alteracao',
        'telefone_fixo',
        'telefone_celular',
    ];

    public function getDataNascimentoAttribute() {
        return Carbon::create($this->attributes['data_nascimento'])->format('d/m/Y');
    }

    public function setDataNascimentoAttribute( $value ) {
        $this->attributes['data_nascimento'] = Carbon::create($value)->format('Y-m-d');
    }

    public function enderecos()
    {
        return $this->hasMany(Endereco::class, 'id_cliente', 'id');
    }

    public function cadastro_em_aberto()
    {
        $passou_primeiro_passo = $this->nome && $this->data_nascimento;
        $passou_segundo_passo = $this->enderecos()->exists();
        $passou_terceiro_passo = $this->telefone_fixo && $this->telefone_celular;

        $passo = 1;

        if ($passou_primeiro_passo) {
            $passo++;
        }
        if ($passou_segundo_passo) {
            $passo++;
        }
        if ($passou_terceiro_passo) {
            $passo++;
        }

        return $passo > 1 && $passo < 4 ? $passo : false;
    }
}
