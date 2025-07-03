<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CargadoDatosController extends Controller
{

    public function getFormData()
    {
        $estaciones = DB::select('CALL sp_estacion_listar()');
        $categorias = DB::select('CALL sp_categoria_platillo_listar()');

        return response()->json([
            'estaciones' => $estaciones,
            'categorias' => $categorias,
        ]);
    }
}
