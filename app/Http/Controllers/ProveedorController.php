<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class ProveedorController extends Controller
{

    public function index()
    {
        $proveedores = DB::select('CALL sp_proveedor_listar()');
        return response()->json($proveedores);
    }

    public function store(Request $request)
    {
        $request->validate([
            'prove_ruc' => 'required',
            'prove_nombre' => 'required',
            'prove_email' => 'required|email',
        ]);

        try {
            $result = DB::selectOne('CALL sp_proveedor_agregar(?, ?, ?, ?, ?)', [
                $request->input('prove_ruc'),
                $request->input('prove_nombre'),
                $request->input('prove_email'),
                $request->input('prove_telefono'),
                $request->input('prove_direccion')
            ]);

            $proveedor = Proveedor::findOrFail($result->new_prove_id);
            return response()->json($proveedor, 201);

        } catch (QueryException $e) {
            return $this->handleDatabaseException($e);
        }
    }

    public function show(Proveedor $proveedor)
    {
        return response()->json($proveedor);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'prove_ruc' => 'required',
            'prove_nombre' => 'required',
            'prove_email' => 'required|email',
        ]);

        try {
            DB::statement('CALL sp_proveedor_editar(?, ?, ?, ?, ?, ?)', [
                $id,
                $request->input('prove_ruc'),
                $request->input('prove_nombre'),
                $request->input('prove_email'),
                $request->input('prove_telefono'),
                $request->input('prove_direccion')
            ]);

            $proveedor = Proveedor::findOrFail($id);
            return response()->json($proveedor);

        } catch (QueryException $e) {
            return $this->handleDatabaseException($e);
        }
    }

    public function destroy($id)
    {
        try {
            DB::statement('CALL sp_proveedor_eliminar(?)', [$id]);
            return response()->json(['message' => 'Proveedor eliminado correctamente.'], 200);

        } catch (QueryException $e) {
            return $this->handleDatabaseException($e, 'Error al eliminar el proveedor.');
        }
    }

    private function handleDatabaseException(QueryException $e, $defaultMessage = 'Error en la base de datos.')
    {
        if ($e->getCode() == '45000') {
            preg_match("/MESSAGE_TEXT = '(.*?)'/", $e->getMessage(), $matches);
            $errorMessage = $matches[1] ?? 'Error de validación en la base de datos.';
            return response()->json(['message' => $errorMessage], 422);
        }

        return response()->json(['message' => $defaultMessage, 'error' => $e->getMessage()], 500);
    }
}
