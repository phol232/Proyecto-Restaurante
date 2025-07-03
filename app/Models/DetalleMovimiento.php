<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleMovimiento extends Model
{
    protected $table = 'detalles_movimiento';
    protected $primaryKey = 'detalle_id';
    public $timestamps = false;

    protected $fillable = [
        'movimiento_id',
        'ingrediente_id',
        'cantidad',
        'precio_unitario',
    ];

    public function movimiento(): BelongsTo
    {
        return $this->belongsTo(Movimiento::class, 'movimiento_id', 'movimiento_id');
    }

    public function ingrediente(): BelongsTo
    {
        return $this->belongsTo(Ingrediente::class, 'ingrediente_id', 'ingrediente_id');
    }
}
