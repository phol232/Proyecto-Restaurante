<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientesController extends Controller
{

    public function index()
    {
        $clientes = Clientes::all();
        return response()->json($clientes);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:clientes,email',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $cliente = Clientes::create($validator->validated());

        return response()->json($cliente, 201);
    }


    public function show(Clientes $cliente)
    {
        return response()->json($cliente);
    }

    public function update(Request $request, Clientes $cliente)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:100',
            'apellido' => 'sometimes|required|string|max:100',
            // Ignora el email del cliente actual al validar que sea único
            'email' => 'sometimes|nullable|email|max:100|unique:clientes,email,' . $cliente->cliente_id . ',cliente_id',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $cliente->update($validator->validated());

        return response()->json($cliente);
    }

    public function destroy(Clientes $cliente)
    {
        $cliente->delete();
        return response()->json(null, 204);
    }
}
