<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $table = 'mesas';
    protected $primaryKey = 'mesa_id';
    protected $fillable = ['nombre', 'capacidad', 'estado'];

    public function ordenes()
    {
        return $this->hasMany(Orden::class, 'mesa_id');
    }
}