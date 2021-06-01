<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $table = 'sectores';
    protected $fillable = [
        'id', 'nombre', 'id_oficina','edo'
    ];
    public $timestamps=false;
}
