<?php
// app/Models/Movimiento.php (Actualizado)

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movimiento extends Model
{
    protected $table = 'movimientos';
    protected $primaryKey = 'movimiento_id';
    public $timestamps = true;

    protected $fillable = [
        'empleado_id',
        'tipo_movimiento_id',
        'prove_id',
        'nota',
        'fecha',
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'empleado_id');
    }

    public function tipoMovimiento(): BelongsTo
    {
        return $this->belongsTo(TipoMovimiento::class, 'tipo_movimiento_id', 'tipo_movimiento_id');
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'prove_id', 'prove_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleMovimiento::class, 'movimiento_id', 'movimiento_id');
    }
}
