<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Endereco;
use App\Estado;
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

        $dados_view = [
            'passo_atual' => $passo_atual,
            'cliente_atual' => $ultimo_cliente
        ];

        if($passo_atual == 2) {
            $dados_view['estados'] = Estado::all();
        }

        return view('clientes.create', $dados_view);
    }

    public function store()
    {
        $ultimo_cliente = Cliente::latest()->first();
        $id_cliente_em_aberto = 0;
        $passo_atual = 1;
        if($ultimo_cliente && $ultimo_cliente->cadastro_em_aberto()) {
            $id_cliente_em_aberto = $ultimo_cliente->id;
            $passo_atual = $ultimo_cliente->cadastro_em_aberto();
        }

        try{
            $dados_validados = request()->validate($this->validacaoPorPasso($passo_atual));
        } catch (Exception $e) {
            Log::info($e);
            return request()->validate($this->validacaoPorPasso($passo_atual));
        }

        if ($passo_atual == 2) {
            $retorno = Endereco::create([
                'rua' => $dados_validados['rua'],
                'numero' => $dados_validados['numero'],
                'cep' => $dados_validados['cep'],
                'id_cliente' => $id_cliente_em_aberto,
                'id_cidade' => $dados_validados['cidade'],
            ]);
        } else {
            $retorno = Cliente::updateOrCreate(
                ['id' => $id_cliente_em_aberto],
                $dados_validados
            );
        }

        $passo_atual = ($passo_atual + 1) % 4;

        return response()->json(['dados_cadastrados' => $retorno, 'passo_atual' => $passo_atual], 200);

    }

    private function validacaoPorPasso($passo) {
        switch($passo) {
            case 1:
                return [
                    'nome' => ['required','string'],
                    'data_nascimento' => ['required','date_format:d/m/Y'],
                ];
                break;
            case 2:
                return [
                    'cidade' => ['required','numeric'],
                    'estado' => ['required', 'numeric'],
                    'rua' => ['required','string'],
                    'numero' => ['required', 'numeric', 'min:0'],
                    'cep' => ['required','string', 'size:8'],

                ];
                break;
            case 3:
                return [
                    'telefone_fixo' => ['required','string', 'min:10'],
                    'telefone_celular' => ['required','string', 'min:11'],
                ];
                break;
        }
    }
}
