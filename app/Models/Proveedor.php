<?php
// app/Models/Proveedor.php (Actualizado)

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';
    protected $primaryKey = 'prove_id';
    public $timestamps = true;

    protected $fillable = [
        'prove_ruc',
        'prove_nombre',
        'prove_email',
        'prove_telefono',
        'prove_direccion',
    ];

    public function movimientos(): HasMany
    {
        return $this->hasMany(Movimiento::class, 'prove_id', 'prove_id');
    }
}
