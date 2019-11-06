<?php

namespace App\Http\Controllers;

use App\Estado;
use Illuminate\Http\Request;

class EstadosController extends Controller
{
    public function index() {
        return response()->json(Estado::all(), 200);
    }
}
