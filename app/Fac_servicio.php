<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fac_servicio extends Model
{
    protected $table = 'fac_servicios';
    protected $fillable = ['id','nombre','desc'];
    public $timestamps = false;

    public function fac_servicio(){
        return $this->hasMany('App\fac_servicios');
    }
}
