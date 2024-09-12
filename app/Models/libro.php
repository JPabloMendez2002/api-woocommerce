<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class libro extends Model
{
    protected $table = 'libro';
    protected $hidden = ['imagen_inicio'];
    use HasFactory;
}
