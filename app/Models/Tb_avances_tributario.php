<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tb_avances_tributario extends Model
{
    protected $table = 'tb_avances_tributario';

    protected $fillable = ['idExterno','cadena','pregunta','enunciado','estado','idUsuario'];

    public $timestamps = false;
}