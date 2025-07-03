<?php


namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'rol_id'        => 'required|exists:roles,rol_id',
            'nombre'        => 'required|string|max:100',
            'apellido'      => 'required|string|max:100',
            'email'         => 'required|email|unique:empleados,email',
            'telefono'      => 'nullable|string|max:20',
            'password'      => 'required|string|min:8|confirmed',
        ]);

        $empleado = Empleado::create([
            'rol_id'        => $data['rol_id'],
            'nombre'        => $data['nombre'],
            'apellido'      => $data['apellido'],
            'email'         => $data['email'],
            'telefono'      => $data['telefono'] ?? null,
            'password'      => Hash::make($data['password']),
            'estado'        => 'activo',
            'fecha_ingreso' => now(),
        ]);

        $token = $empleado->createToken('api_token')->plainTextToken;
        return response()->json(['empleado' => $empleado, 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $empleado = Empleado::where('email', $credentials['email'])->first();

        if (
            ! $empleado ||
            ! Hash::check($credentials['password'], $empleado->password) ||
            $empleado->estado !== 'activo'
        ) {
            return response()->json(['message' => 'Credenciales inválidas o usuario inactivo'], 401);
        }

        $token = $empleado->createToken('api_token')->plainTextToken;
        return response()->json(['empleado' => $empleado, 'token' => $token]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
