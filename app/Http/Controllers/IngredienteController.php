<?php

namespace App\Http\Controllers;

use App\Models\Ingrediente;
use Illuminate\Http\Request;

class IngredienteController extends Controller
{
    public function index()
    {
        return response()->json(Ingrediente::with('categoria')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'categoria_id'   => 'required|exists:categorias_ingredientes,categoria_id',
            'nombre'         => 'required|string|max:100',
            'descripcion'    => 'nullable|string|max:255',
            'costo_unitario' => 'required|numeric',
            'unidad'         => 'nullable|string|max:20',
            'stock_actual'   => 'integer|min:0',
            'stock_minimo'   => 'integer|min:0',
            'estado'         => 'required|in:disponible,agotado,descontinuado',
        ]);

        $ing = Ingrediente::create($data);

        return response()->json($ing, 201);
    }

    public function show($id)
    {
        $ing = Ingrediente::with('categoria')->findOrFail($id);
        return response()->json($ing);
    }

    public function update(Request $request, $id)
    {
        $ing = Ingrediente::findOrFail($id);

        $data = $request->validate([
            'categoria_id'   => 'required|exists:categorias_ingredientes,categoria_id',
            'nombre'         => 'required|string|max:100',
            'descripcion'    => 'nullable|string|max:255',
            'costo_unitario' => 'required|numeric',
            'unidad'         => 'nullable|string|max:20',
            'stock_actual'   => 'integer|min:0',
            'stock_minimo'   => 'integer|min:0',
            'estado'         => 'required|in:disponible,agotado,descontinuado',
        ]);

        $ing->update($data);

        return response()->json($ing);
    }

    public function destroy($id)
    {
        Ingrediente::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
