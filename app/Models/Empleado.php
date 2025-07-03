<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Empleado extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'empleados';
    protected $primaryKey = 'empleado_id';

    protected $fillable = [
        'rol_id', 'nombre', 'apellido', 'email', 'telefono',
        'fecha_ingreso', 'estado', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
    ];

    public function rol()
    {
        return $this->belongsTo(Role::class, 'rol_id');
    }
}
