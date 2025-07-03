<?php
// app/Models/EstacionCocina.php (AÑADIDO)

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstacionCocina extends Model
{
    use HasFactory;

    protected $table = 'estaciones_cocina';
    protected $primaryKey = 'estacion_id';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function platillos(): HasMany
    {
        return $this->hasMany(Platillo::class, 'estacion_id', 'estacion_id');
    }
}
