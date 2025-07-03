<?php

namespace App\Http\Controllers;

use App\Models\Platillo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;

class PlatilloController extends Controller
{
    public function index()
    {
        $platillos = DB::select('CALL sp_platillo_listar()');

        foreach ($platillos as $platillo) {
            // Decodificar el JSON de ingredientes
            if (isset($platillo->ingredientes)) {
                $platillo->ingredientes = json_decode($platillo->ingredientes);
            } else {
                $platillo->ingredientes = [];
            }

            // Generar la URL completa de la imagen SOLO si existe un nombre de archivo
            if (!empty($platillo->plat_imagen)) {
                $platillo->plat_imagen_url = asset('storage/' . $platillo->plat_imagen);
            } else {
                $platillo->plat_imagen_url = null; // Enviar null si no hay imagen
            }
        }

        return response()->json($platillos);
    }

    // ... (El resto de los métodos store, update, destroy no necesitan cambios)

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:100',
            'precio_venta' => 'required|numeric|min:0',
            'plat_imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'estacion_id' => 'nullable|exists:estaciones_cocina,estacion_id',
            'categoria_id' => 'nullable|exists:categorias_platillos,categoria_id',
            'ingredientes' => 'required|json',
        ]);

        $imagePath = null;
        if ($request->hasFile('plat_imagen')) {
            $imagePath = $request->file('plat_imagen')->store('platillos', 'public');
        }

        try {
            $result = DB::selectOne('CALL sp_platillo_agregar(?, ?, ?, ?, ?, ?, ?)', [
                $validatedData['nombre'],
                $request->input('descripcion'),
                $validatedData['precio_venta'],
                $imagePath,
                $validatedData['estacion_id'] ?? null,
                $validatedData['categoria_id'] ?? null,
                $validatedData['ingredientes']
            ]);

            $platillo = Platillo::with('ingredientes', 'estacionCocina', 'categoriaPlatillo')->findOrFail($result->new_platillo_id);
            return response()->json($platillo, 201);

        } catch (QueryException $e) {
            if ($imagePath) Storage::disk('public')->delete($imagePath);
            return $this->handleDatabaseException($e);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:100',
            'precio_venta' => 'required|numeric|min:0',
            'plat_imagen' => 'nullable|sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'estacion_id' => 'nullable|exists:estaciones_cocina,estacion_id',
            'categoria_id' => 'nullable|exists:categorias_platillos,categoria_id',
            'ingredientes' => 'required|json',
        ]);

        $platillo = Platillo::findOrFail($id);
        $imagePath = $platillo->plat_imagen;

        if ($request->hasFile('plat_imagen')) {
            if ($imagePath) Storage::disk('public')->delete($imagePath);
            $imagePath = $request->file('plat_imagen')->store('platillos', 'public');
        }

        try {
            DB::statement('CALL sp_platillo_editar(?, ?, ?, ?, ?, ?, ?, ?)', [
                $id,
                $validatedData['nombre'],
                $request->input('descripcion'),
                $validatedData['precio_venta'],
                $imagePath,
                $validatedData['estacion_id'] ?? null,
                $validatedData['categoria_id'] ?? null,
                $validatedData['ingredientes']
            ]);

            $platilloActualizado = Platillo::with('ingredientes', 'estacionCocina', 'categoriaPlatillo')->findOrFail($id);
            return response()->json($platilloActualizado);

        } catch (QueryException $e) {
            return $this->handleDatabaseException($e);
        }
    }

    public function destroy($id)
    {
        $platillo = Platillo::findOrFail($id);
        $imagePath = $platillo->plat_imagen;
        try {
            DB::statement('CALL sp_platillo_eliminar(?)', [$id]);
            if ($imagePath) Storage::disk('public')->delete($imagePath);
            return response()->json(['message' => 'Platillo eliminado correctamente.'], 200);
        } catch (QueryException $e) {
            return $this->handleDatabaseException($e);
        }
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
