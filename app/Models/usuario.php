<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class usuario extends Model
{
    protected $table = 'usuario';
    protected $fillable = ['nombre' , 'correo' , 'telefono' , 'contrasena' , 'fecha_inicio' , 'fecha_vencimiento' , 'sesiones_activas' , 'estado' , 'primer_ingreso' ];
    public $timestamps = false;
    use HasFactory;
}
