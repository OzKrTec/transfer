<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barrio extends Model
{
    protected $table = 'barrios';
    protected $fillable = [
        'id', 'nombre', 'id_sector','edo'
    ];
    public $timestamps=false;
}
