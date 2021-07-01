<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facturas extends Model
{
    //
    protected $table = 'facturas';
    protected $fillable = [
        'id', 'num_fact', 'niu','edo','valor','concepto','mes','ano','is_pago'
    ];
    public $timestamps=false;
}
