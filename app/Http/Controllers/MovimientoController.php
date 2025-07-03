<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class MovimientoController extends Controller
{
    public function index()
    {
        $movimientos = DB::select('CALL sp_movimiento_listar()');

        foreach ($movimientos as $movimiento) {
            if (isset($movimiento->detalles)) {
                $movimiento->detalles = json_decode($movimiento->detalles);
            }
        }
        return response()->json($movimientos);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tipo_movimiento_id' => 'required|exists:tipos_movimiento,tipo_movimiento_id',
            'prove_id' => 'nullable|exists:proveedores,prove_id',
            'detalles' => 'required|array|min:1',
            'detalles.*.ingrediente_id' => 'required|exists:ingredientes,ingrediente_id',
            'detalles.*.cantidad' => 'required|numeric|min:0.01',
        ]);

        try {
            $detallesJson = json_encode($validatedData['detalles']);

            $result = DB::selectOne('CALL sp_movimiento_agregar(?, ?, ?, ?, ?)', [
                Auth::id(),
                $validatedData['tipo_movimiento_id'],
                $validatedData['prove_id'] ?? null,
                $request->input('nota'),
                $detallesJson
            ]);

            $movimiento = Movimiento::with('detalles.ingrediente', 'proveedor', 'empleado', 'tipoMovimiento')->findOrFail($result->new_movimiento_id);
            return response()->json($movimiento, 201);

        } catch (QueryException $e) {
            return $this->handleDatabaseException($e);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'tipo_movimiento_id' => 'required|exists:tipos_movimiento,tipo_movimiento_id',
            'prove_id' => 'nullable|exists:proveedores,prove_id',
            'detalles' => 'required|array|min:1',
            'detalles.*.ingrediente_id' => 'required|exists:ingredientes,ingrediente_id',
            'detalles.*.cantidad' => 'required|numeric|min:0.01',
        ]);

        try {
            $detallesJson = json_encode($validatedData['detalles']);

            DB::statement('CALL sp_movimiento_editar(?, ?, ?, ?, ?, ?)', [
                $id,
                Auth::id(),
                $validatedData['tipo_movimiento_id'],
                $validatedData['prove_id'] ?? null,
                $request->input('nota'),
                $detallesJson
            ]);

            $movimiento = Movimiento::with('detalles.ingrediente', 'proveedor', 'empleado', 'tipoMovimiento')->findOrFail($id);
            return response()->json($movimiento);

        } catch (QueryException $e) {
            return $this->handleDatabaseException($e);
        }
    }

    public function destroy($id)
    {
        try {
            DB::select('CALL sp_movimiento_eliminar(?)', [$id]);
            return response()->json(['message' => 'Movimiento eliminado y stock revertido.'], 200);
        } catch (QueryException $e) {
            return $this->handleDatabaseException($e);
        }
    }

    public function show(Movimiento $movimiento)
    {
        return response()->json($movimiento->load(['empleado', 'tipoMovimiento', 'proveedor', 'detalles.ingrediente']));
    }

    private function handleDatabaseException(QueryException $e)
    {
        if ($e->getCode() == '45000') {
            preg_match("/MESSAGE_TEXT = '(.*?)'/", $e->getMessage(), $matches);
            $errorMessage = $matches[1] ?? 'Error de validación en la base de datos.';
            return response()->json(['message' => $errorMessage], 422);
        }

        return response()->json(['message' => 'Error en la base de datos.', 'error' => $e->getMessage()], 500);
    }
}
