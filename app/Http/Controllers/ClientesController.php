<?php

namespace App\Http\Controllers;

use App\Cliente;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    public function index()
    {
        $ultimo_cliente = Cliente::latest()->first();
        $passo_atual = 1;

        if($ultimo_cliente && $ultimo_cliente->cadastro_em_aberto()) {
            $passo_atual = $ultimo_cliente->cadastro_em_aberto();
        }

        return view('clientes.create', [
            'passo_atual' => $passo_atual,
            'cliente_atual' => $ultimo_cliente
        ]);
    }

    public function store()
    {
        $ultimo_cliente = Cliente::latest()->first();
        $id_cliente_em_aberto = 0;

        if($ultimo_cliente && $ultimo_cliente->cadastro_em_aberto()) {
            $id_cliente_em_aberto = $ultimo_cliente->id;
        }

        $cliente = Cliente::updateOrCreate(
            ['id' => $id_cliente_em_aberto],
            request()
        );

        return redirect('/');
    }
}
