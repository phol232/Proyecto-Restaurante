<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoMovimiento extends Model
{
    protected $table = 'tipos_movimiento';
    protected $primaryKey = 'tipo_movimiento_id';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}
