<?php
namespace App\Http\Controllers;

use App\Models\Mesa;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    public function index()
    {
        return Mesa::all();
    }

    public function show(Mesa $mesa)
    {
        return $mesa;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:50',
            'capacidad' => 'required|integer|min:1',
            'estado'    => 'required|in:libre,ocupada,reservada',
        ]);

        return Mesa::create($data);
    }

    public function update(Request $request, Mesa $mesa)
    {
        $data = $request->validate([
            'nombre'    => 'sometimes|required|string|max:50',
            'capacidad' => 'sometimes|required|integer|min:1',
            'estado'    => 'sometimes|required|in:libre,ocupada,reservada',
        ]);

        $mesa->update($data);
        return $mesa;
    }

    public function destroy(Mesa $mesa)
    {
        $mesa->delete();
        return response()->json([
            'message' => 'Mesa eliminada correctamente'
        ], 200);
    }
}
