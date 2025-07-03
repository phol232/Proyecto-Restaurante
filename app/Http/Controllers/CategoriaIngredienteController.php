<?php

namespace App\Http\Controllers;

use App\Models\CategoriaIngrediente;
use Illuminate\Http\Request;

class CategoriaIngredienteController extends Controller
{
    public function index()
    {
        return response()->json(CategoriaIngrediente::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $cat = CategoriaIngrediente::create($data);

        return response()->json($cat, 201);
    }

    public function show($id)
    {
        $cat = CategoriaIngrediente::with('ingredientes')->findOrFail($id);
        return response()->json($cat);
    }

    public function update(Request $request, $id)
    {
        $cat = CategoriaIngrediente::findOrFail($id);

        $data = $request->validate([
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $cat->update($data);

        return response()->json($cat);
    }

    public function destroy($id)
    {
        CategoriaIngrediente::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
