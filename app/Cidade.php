<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    protected $fillable = [
        'id_estado',
        'codigo',
        'nome',
    ];

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado', 'id');
    }
}
