<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingrediente extends Model
{
    use HasFactory;

    protected $table = 'ingredientes';
    protected $primaryKey = 'ingrediente_id';
    public $timestamps = true;

    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'costo_unitario',
        'unidad',
        'stock_actual',
        'stock_minimo',
        'estado',
    ];

    /**
     * Un ingrediente pertenece a una categoría.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaIngrediente::class, 'categoria_id', 'categoria_id');
    }

    public function platillos(): BelongsToMany
    {
        return $this->belongsToMany(Platillo::class, 'platillo_ingredientes', 'ingrediente_id', 'platillo_id');
    }
}
