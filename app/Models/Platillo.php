<?php
// app/Models/Platillo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Platillo extends Model
{
    use HasFactory;

    protected $table = 'platillos';
    protected $primaryKey = 'platillo_id';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_venta',
        'plat_imagen',
        'estacion_id',
        'categoria_id',
    ];

    public function estacionCocina(): BelongsTo
    {
        return $this->belongsTo(EstacionCocina::class, 'estacion_id', 'estacion_id');
    }

    public function categoriaPlatillo(): BelongsTo
    {
        return $this->belongsTo(CategoriaPlatillo::class, 'categoria_id', 'categoria_id');
    }

    public function ingredientes(): BelongsToMany
    {
        return $this->belongsToMany(Ingrediente::class, 'platillo_ingredientes', 'platillo_id', 'ingrediente_id')
            ->withPivot('cantidad', 'unidad');
    }
}
