<?php

namespace App\Http\Controllers;

use App\Cidade;
use Illuminate\Http\Request;
use Log;

class CidadesController extends Controller
{
    public function listarPorEstado($id_estado)
    {
        return response()->json(
            Cidade::where(['id_estado' => $id_estado])->get(),
            200
        );
    }
}
