<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Role extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'rol_id';
    public $timestamps = true;

    protected $fillable = [
        'rol_nombre',
        'rol_color',
        'rol_descripcion'
    ];

    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'rol_id');
    }
}