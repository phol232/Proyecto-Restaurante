<?php
namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index()
    {
        return Empleado::all();
    }

    public function show(Empleado $empleado)
    {
        return $empleado;
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'rol_id'   => 'required|exists:roles,rol_id',
        'nombre'   => 'required|string|max:100',
        'apellido' => 'required|string|max:100',
        'email'    => 'required|email|unique:empleados,email',
        'password' => 'required|string|min:8',
        'telefono' => 'nullable|string|max:20',
        'estado'   => 'required|in:activo,inactivo',
    ]);

    $data['password'] = Hash::make($data['password']);

    return response()->json(Empleado::create($data), 201);
}

    public function update(Request $request, Empleado $empleado)
    {
        $data = $request->validate([
            'rol_id'   => 'sometimes|required|exists:roles,rol_id',
            'nombre'   => 'sometimes|required|string|max:100',
            'apellido' => 'sometimes|required|string|max:100',
            'email'    => 'sometimes|required|email|unique:empleados,email,' . $empleado->empleado_id . ',empleado_id',
            'password' => 'sometimes|nullable|string|min:8',
            'telefono' => 'nullable|string|max:20',
            'estado'   => 'sometimes|required|in:activo,inactivo',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $empleado->update($data);
        return response()->json(['empleado' => $empleado], 200);
    }

    public function destroy(Empleado $empleado)
    {
        $empleado->tokens()->delete();

        $empleado->delete();

        return response()->json([
            'message' => 'Empleado eliminado correctamente'
        ], 200);
    }
}