<?php

namespace App\Http\Controllers;

use App\Cliente;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClientesController extends Controller
{
    public function create()
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
        // try{
            return request()->validate([
                'nome' => 'required',
                'data_nascimento' => 'required',
            ]);
        // } catch (Exception $e) {
        //     Log::info($e);
        //     return response()->json(['dados_inválidos'], 500);
        // }
        log::info($dados_validados);
        return response()->json([], 200);
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
