<?php
// app/Models/CategoriaPlatillo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriaPlatillo extends Model
{
    use HasFactory;

    protected $table = 'categorias_platillos';
    protected $primaryKey = 'categoria_id';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function platillos(): HasMany
    {
        return $this->hasMany(Platillo::class, 'categoria_id', 'categoria_id');
    }
}
