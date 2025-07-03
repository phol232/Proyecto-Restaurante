<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriaIngrediente extends Model
{
    protected $table = 'categorias_ingredientes';
    protected $primaryKey = 'categoria_id';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function ingredientes(): HasMany
    {
        return $this->hasMany(Ingrediente::class, 'categoria_id', 'categoria_id');
    }
}
