<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Clientes extends Model
{
    use HasFactory;


    protected $table = 'clientes';

    protected $primaryKey = 'cliente_id';


    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'direccion',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
