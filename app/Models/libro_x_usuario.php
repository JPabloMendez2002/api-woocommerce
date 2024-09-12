<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class libro_x_usuario extends Model
{
    protected $table = 'libro_x_usuario';
    protected $fillable = ['id_libro' , 'id_usuario'];
    public $timestamps = false;
    use HasFactory;
}
